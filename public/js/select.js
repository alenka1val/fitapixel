$('.dropdown').click(function () {
    $(this).attr('tabindex', 1).focus();
    $(this).toggleClass('active');
    $(this).find('.dropdown-menu').slideToggle(300);
});
$('.dropdown').focusout(function () {
    $(this).removeClass('active');
    $(this).find('.dropdown-menu').slideUp(300);
});
$('.dropdown .dropdown-menu li').click(setSelect);

function setSelect(){
    
    $(this).parents('.dropdown').find('span').text($(this).text());
    $(this).parents('.dropdown').find('input').attr('value', $(this).attr('id'));
    
    
    if(this.parentElement!=null){
        if(this.parentElement.parentElement.classList.contains('selected_year')){

            let parent = document.getElementById('event_list');
            let selected_year = document.getElementById("selected_year").value;
            
            //clear items
            parent.innerHTML = '';
    
            //add new items
            var events_in_year = events[selected_year];
    
            events_in_year.forEach(event => {
                let li = document.createElement("li");
                li.setAttribute('id',event.id);
                li.appendChild(document.createTextNode(event.name));
                parent.appendChild(li);
                $('#event_list #'+event.id).on('click', setSelect);
            });

            //clear selected events
            document.getElementById('selected_event').setAttribute('value', '');
            document.getElementById('selected-item-name').innerHTML = 'Vyberte súťaž';
        }
    }
}

/*End Dropdown Menu*/

// $('.dropdown-menu li').click(function () {
//     console.log('ok4');
//     document.getElementById("selected_year").value = $(this).parents('.dropdown').find('input').val();
// });

