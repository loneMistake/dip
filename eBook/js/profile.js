document.addEventListener('DOMContentLoaded', function() {
    var deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault(); 
            var cardId = this.getAttribute('data-card-id');
            var confirmDelete = confirm('Вы уверены, что хотите удалить эту карточку?');
            if (confirmDelete) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_card.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = xhr.responseText;
                            if (response === 'success') {
                                var card = button.closest('.card');
                                if (card) {
                                    card.remove();
                                }
                            } else {
                                alert('Ошибка удаления карточки.');
                            }
                        } else {
                            alert('Ошибка удаления карточки.');
                        }
                    }
                };
                xhr.send('card_id=' + cardId);
            }
        });
    });
});
document.getElementById("editDataBtn").addEventListener("click", function(event) {
    // Очистить поля формы перед открытием модального окна
    document.getElementById("editDataForm").reset();
});


    function validateForm() {
        var phoneNumber = document.getElementById("phoneNumber").value;
        var phoneNumberPattern = /^[0-9]{10}$/;

        if (!phoneNumberPattern.test(phoneNumber)) {
            alert("Введите 10 цифр в номере телефона.");
            return false;
        }

        // Дополнительные проверки, если необходимо

        return true;
    }