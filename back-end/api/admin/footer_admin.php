</div> </div> <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }

        
            hamburgerBtn.addEventListener('click', toggleSidebar);

            
            overlay.addEventListener('click', toggleSidebar);
        });
    </script>

</body>
</html>