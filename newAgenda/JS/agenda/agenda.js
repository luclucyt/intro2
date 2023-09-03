document.getElementById('filter-functie').addEventListener('input', function (){
           
    //if there is a filter, hide all agenda items that don't have the same class as the filter
    if(this.value !== "0") {
        let agendaItems = document.querySelectorAll('.agenda-item');
        for(let i = 0; i < agendaItems.length; i++) {
            if(agendaItems[i].classList.contains(this.value)) {
                agendaItems[i].style.opacity = '1';
                agendaItems[i].style.boxShadow = 'rgb(255 255 255) 0px 0px 100px 10px';
            } else {
                agendaItems[i].style.opacity = '0.5';
                agendaItems[i].style.boxShadow = 'none';
            }
        }
    } else {
        //if there is no filter, show all agenda items
        let agendaItems = document.querySelectorAll('.agenda-item');
        for(let i = 0; i < agendaItems.length; i++) {
            agendaItems[i].style.opacity = '1';
            agendaItems[i].style.boxShadow = 'none';
        }
    }
});


//make sure the agenda grid is scrolled down a bit
document.getElementsByClassName("agenda-grid-wrapper")[0].scrollTop = 400;





// Get all forms with the class 'agenda-form'
const forms = document.querySelectorAll('.agenda-form');

forms.forEach(form => {
    const inputField = form.querySelector('.agenda-item-naam');
    const inputField2 = form.querySelector('.agenda-item-omschrijving');
    const inputField3 = form.querySelector('.agenda-item-start-tijd');
    const inputField4 = form.querySelector('.agenda-item-eind-tijd');
    
    inputField.addEventListener('blur', function () {
        updateAgendaItem(form);
    });
    inputField2.addEventListener('blur', function () {
        updateAgendaItem(form);
    });
    inputField3.addEventListener('blur', function () {
        updateAgendaItem(form);
    });
    inputField4.addEventListener('blur', function () {
        updateAgendaItem(form);
    });

});

function updateAgendaItem(form){
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    const phpScriptURL = 'index.php';

    xhr.open('POST', phpScriptURL, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onload = function () {};

    xhr.send(formData);
}



const agendaItems = document.querySelectorAll('.agenda-item');
let isChangingTime = false;
let draggedItem = null;
let topInput = false;
let bottomInput = false;

agendaItems.forEach((agendaItem) => {
    const upScale = agendaItem.querySelector('.change-time-item-top');
    const downScale = agendaItem.querySelector('.change-time-item-bottom');
    const startTijdInput = agendaItem.querySelector('.agenda-item-start-tijd');
    const endTijdInput = agendaItem.querySelector('.agenda-item-eind-tijd');

    upScale.addEventListener('mousedown', function (event) {
        agendaGrid.style.cursor = 'ns-resize';
        isChangingTime = true;
        draggedItem = agendaItem;
        event.stopPropagation();
        topInput = true;
    });

    document.addEventListener('mousemove', function (event) {
        if (isChangingTime && draggedItem === agendaItem && topInput) {
            let rect = agendaGrid.getBoundingClientRect();
            let y = event.clientY - rect.top;
            let row_height = rect.height / 96; // Assuming you have 96 rows as per your CSS.

            let time = Math.floor(y / row_height) * 15;
            let hours = Math.floor(time / 60);
            let minutes = time % 60;

            let startTijd = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');
            
            startTijdInput.value = startTijd;
            agendaItem.style.gridRowStart = Math.floor(y / row_height) + 1;
        }
    });

    document.addEventListener('mouseup', function () {
        if (isChangingTime && draggedItem === agendaItem && topInput) {
            agendaGrid.style.cursor = 'default';
            topInput = false;
            isChangingTime = false;
            draggedItem = null;

            updateAgendaItem(agendaItem.querySelector('.agenda-form'));
        }
    });


    downScale.addEventListener('mousedown', function (event) {
        agendaGrid.style.cursor = 'ns-resize';
        bottomInput = true;
        isChangingTime = true;
        draggedItem = agendaItem;
        event.stopPropagation();
    });

    document.addEventListener('mousemove', function (event) {
        if (isChangingTime && draggedItem === agendaItem && bottomInput) {
            let rect = agendaGrid.getBoundingClientRect();
            let y = event.clientY - rect.top;
            let row_height = rect.height / 96; // Assuming you have 96 rows as per your CSS.

            let time = Math.floor(y / row_height) * 15;
            let hours = Math.floor(time / 60);
            let minutes = time % 60;

            let endTijd = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');
            
            endTijdInput.value = endTijd;
            agendaItem.style.gridRowEnd = Math.floor(y / row_height) + 1;
        }
    });

    document.addEventListener('mouseup', function () {
        if (isChangingTime && draggedItem === agendaItem && bottomInput) {
            agendaGrid.style.cursor = 'default';
            bottomInput = false;
            isChangingTime = false;
            draggedItem = null;

            updateAgendaItem(agendaItem.querySelector('.agenda-form'));
        }
    });
});
