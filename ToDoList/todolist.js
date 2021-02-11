const draggables = document.querySelectorAll('.draggable')
const containers = document.querySelectorAll('.container')

draggables.forEach(draggable => {
    draggable.addEventListener('dragstart', () => {
        draggable.classList.add('dragging')
    })

    draggable.addEventListener('dragend', () => {
        var categ = draggable.parentElement.parentElement.id;
        var date = new Date();

        if (categ == "today") date.setDate(date.getDate());
        else if (categ == "demain") date.setDate(date.getDate() + 1);
        else if (categ == "ap") date.setDate(date.getDate() + 2);
        else if (categ == "apap") date.setDate(date.getDate() + 3);
        else if (categ == "reste" || categ == "retard") {
            draggable.classList.remove('dragging');
            window.location.reload();
            return;
        }

        var date_ = date.toISOString().slice(0, 10);
        $.ajax({
            url: './edit.php',
            type: "POST",
            data: { id: draggable.id, new_date: date_ },
            success: function (rep) {
                if (rep != 'OK') {
                    alert(rep);
                }
                else {
                    window.location.reload();
                }
            }
        });

        draggable.classList.remove('dragging')
    })
})

containers.forEach(container => {
    container.addEventListener('dragover', e => {
        e.preventDefault()
        const afterElement = getDragAfterElement(container, e.clientY)
        const draggable = document.querySelector('.dragging')
        if (afterElement == null) {
            container.appendChild(draggable)
        } else {
            container.insertBefore(draggable, afterElement)
        }
    })
})

function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.draggable:not(.dragging)')]

    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect()
        const offset = y - box.top - box.height / 2
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child }
        } else {
            return closest
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element
}