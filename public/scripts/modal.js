document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems, open);

    //это скрипт для модального окна для вывода что пользователь не авторизован

    // var instance = M.Modal.getInstance(document.querySelector(".modal"));

    // instance.open(); // запуск открытия модального окна при перезагрузке стр. эту фугкцию будем запускать в Controller ветке else.
});
