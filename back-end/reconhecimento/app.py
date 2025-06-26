import face_recognition
import numpy as np
import os
from flask import Flask, request, jsonify
from flask_cors import CORS
import cv2
import imutils

app = Flask(__name__)
CORS(app)

# --- Preparação: Carregar rostos conhecidos na inicialização ---
known_face_encodings = []
known_face_names = []
DATABASE_PATH = 'database'

print("Carregando banco de dados de rostos...")

# Itera sobre cada pasta de pessoa no banco de dados
for person_name in os.listdir(DATABASE_PATH):
    person_dir = os.path.join(DATABASE_PATH, person_name)
    if os.path.isdir(person_dir):
        # Itera sobre cada imagem da pessoa
        for filename in os.listdir(person_dir):
            if filename.lower().endswith(('.png', '.jpg', '.jpeg')):
                image_path = os.path.join(person_dir, filename)
                # Carrega a imagem e gera a codificação facial
                try:
                    face_image = face_recognition.load_image_file(image_path)
                    face_encodings = face_recognition.face_encodings(face_image)
                    
                    # Garante que encontrou exatamente um rosto na imagem de referência
                    if len(face_encodings) == 1:
                        known_face_encodings.append(face_encodings[0])
                        known_face_names.append(person_name)
                        print(f"Rosto de {person_name} carregado com sucesso.")
                    else:
                        print(f"AVISO: Não foi possível encontrar um rosto claro ou há múltiplos rostos em {image_path}. Pulando.")

                except Exception as e:
                    print(f"Erro ao processar {image_path}: {e}")

print(f"{len(known_face_names)} rostos carregados no banco de dados.")
# Lista completa de todos os alunos cadastrados
all_students = list(set(known_face_names))


# --- API Endpoint para Reconhecimento ---
# Renomeei a rota para ser mais descritiva
@app.route('/recognize_faces', methods=['POST'])
def recognize_faces():
    if 'image' not in request.files:
        return jsonify({"error": "Nenhum arquivo de imagem enviado"}), 400

    file = request.files['image']
    img_bytes = file.read()
    
    # Decodifica a imagem para o formato do OpenCV
    npimg = np.frombuffer(img_bytes, np.uint8)
    image_stream = cv2.imdecode(npimg, cv2.IMREAD_COLOR)
    
    # Converte a imagem de BGR (OpenCV) para RGB (face_recognition)
    rgb_image = cv2.cvtColor(image_stream, cv2.COLOR_BGR2RGB)

    # Detecta a localização de todos os rostos na imagem
    face_locations = face_recognition.face_locations(rgb_image)
    # Codifica os rostos detectados
    face_encodings = face_recognition.face_encodings(rgb_image, face_locations)

    recognized_faces = []
    present_students = set()

    # Itera sobre cada rosto encontrado na imagem
    for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
        # Compara o rosto encontrado com todos os rostos conhecidos
        matches = face_recognition.compare_faces(known_face_encodings, face_encoding, tolerance=0.6)
        name = "Desconhecido"

        # Encontra a melhor correspondência
        face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
        if len(face_distances) > 0:
            best_match_index = np.argmin(face_distances)
            if matches[best_match_index]:
                name = known_face_names[best_match_index]
                present_students.add(name)

        # Adiciona o resultado à lista
        recognized_faces.append({
            "name": name,
            "location": {"top": top, "right": right, "bottom": bottom, "left": left}
        })
    
    # Calcula a lista de ausentes
    absent_students = [student for student in all_students if student not in present_students]

    # Retorna o resultado completo
    return jsonify({
        "recognized_faces": recognized_faces,
        "present_students": list(present_students),
        "absent_students": absent_students
    })

if __name__ == '__main__':
    # Use 0.0.0.0 para ser acessível na sua rede local, se necessário
    app.run(host='127.0.0.1', port=5000, debug=True)