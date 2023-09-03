const accordionHeaders = document.querySelectorAll('.accordion-item-header');

accordionHeaders.forEach(header => {
    header.addEventListener('click', function (e) {
        const accordionItem = header.parentElement;
        const accordionContent = accordionItem.querySelector('.accordion-content');

        if (accordionItem.classList.contains('open')) {
            accordionItem.classList.remove('open');
            accordionContent.style.maxHeight = '0';
        } else {
            // Close all other open accordions
            document.querySelectorAll('.accordion-item.open').forEach(openAccordionItem => {
                const openAccordionContent = openAccordionItem.querySelector('.accordion-content');
                openAccordionItem.classList.remove('open');
                openAccordionContent.style.maxHeight = '0';
            });

            // Open the clicked accordion
            accordionItem.classList.add('open');
            //change the height to the 'wanted' height in px
            accordionContent.style.maxHeight = accordionContent.scrollHeight + 'px';
        }
    });
});



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
