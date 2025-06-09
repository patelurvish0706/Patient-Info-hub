const tabButtons = document.querySelectorAll('.tabButton');

tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove active from all
        tabButtons.forEach(btn => btn.classList.remove('active'));
        // Add active to clicked one
        button.classList.add('active');
    });
});

// ----- Pending : Chnge tab view by Clicking Buttons. -----