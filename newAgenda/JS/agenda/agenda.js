let agenda_view_users = document.getElementsByClassName("agenda-view-users")

// if any of the agenda-view-users elements gets checked (or unchecked)
// then loop troug .agenda-item and show them only if the have the same class as one of the checked elements
for (let i = 0; i < agenda_view_users.length; i++) {
    agenda_view_users[i].addEventListener("change", function() {

        let agenda_items = document.getElementsByClassName("agenda-item")
        for (let j = 0; j < agenda_items.length; j++) {

            agenda_items[j].style.opacity = "0.2"
            for (let k = 0; k < agenda_view_users.length; k++) {
                if (agenda_view_users[k].checked && agenda_items[j].classList.contains(agenda_view_users[k].value)) {
                    agenda_items[j].style.opacity = "1"
                }
            }
        }
    })
}


function updateShare(username){
    let shareInputValue = username

    let xhr = new XMLHttpRequest()
    xhr.open("POST", "index.php", true)
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.send("shareInputValue=" + shareInputValue)

    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            // Create a new HTML document from the response text
            let parser = new DOMParser();
            let htmlDoc = parser.parseFromString(this.responseText, 'text/html');

            // Get the first element with class "reponesForHTML"
            let reponesForHTML = htmlDoc.querySelector('.reponesForHTML');

            // Extract the HTML content of the element and its children
            let content = reponesForHTML.innerHTML;

            // Update the HTML content of an element on the page with the extracted content
            document.getElementById("result").innerHTML = content;

            document.getElementsByClassName(`share-display-form-input`)[0].value = username;
        }
    }
}





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


document.getElementsByClassName("agenda-grid-wrapper")[0].scrollTop = 400;