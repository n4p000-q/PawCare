</main> <!-- Close content-wrapper -->

<footer class="client-footer">
    <div class="footer-content">
        <p>&copy; <?= date('Y') ?> Paws & Care Veterinary System</p>
        <div class="footer-links">
            <a href="../client-portal/privacy.php">Privacy Policy</a>
            <a href="../client-portal/contact.php">Contact</a>
        </div>
    </div>
</footer>
<script>
    // Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    const icon = themeToggle.querySelector('i');
    
    // Check for saved theme preference
    const currentTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);
    
    if (currentTheme === 'dark') {
        icon.classList.replace('fa-moon', 'fa-sun');
    }
    
    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        if (newTheme === 'dark') {
            icon.classList.replace('fa-moon', 'fa-sun');
        } else {
            icon.classList.replace('fa-sun', 'fa-moon');
        }
    });
</script>
</body>
</html>