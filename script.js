// Debounce Function for Dragmove
const debounce = (func, wait = 40, immediate = true) => {
    let timeout;
  
    return (...args) => {
      const context = this;
      const later = () => {
          timeout = null;
          if (!immediate) func.apply(context, args);
        };
        
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        
        if (callNow) func.apply(context, args);
    };
};

const del = document.querySelectorAll(".deletetab");
const check = document.querySelectorAll(".donetab");
const color = document.querySelectorAll(".colortab");
const draggertabs = document.querySelectorAll(".draggertab");
const list = document.getElementById("list");
const curItems = list.querySelectorAll(".item");
let curOrder = Array.from(curItems).map(item => item.id);
const spans = list.querySelectorAll('.item span');

function askForDoubleClick(e) {
    const item = e.target;

    item.addEventListener('click', deleteItem);

    if(!item.classList.contains('reveal')){
        item.classList.add('reveal');
    }else {
        item.classList.remove('reveal');
    }

    setTimeout(() => {
        item.removeEventListener('click', deleteItem);
    },2000);
}

function deleteItem(e) {
    var formData = new FormData();
    formData.append('id', e.target.id);
    const listItem = this.parentNode;

    fetch('app/remove.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if(data){
            listItem.classList.add('hidden');
            setTimeout(() => listItem.style.display = 'none', 600)
            const getItems = list.querySelectorAll(".item");
            curOrder = Array.from(getItems).map(item => item.id);
        }
    })
    .catch(error => {
        console.log('Request error:', error);
        // Handle the error
    });
}

function checkItem(e){
    const listItem = this.parentNode;
    var formData = new FormData();
    formData.append('id', e.target.id);
    fetch('app/check.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        if(data !== "error" ){
            if(data === '1') listItem.classList.add('crossout');
            else listItem.classList.remove('crossout');
        }
    })
    .catch(error => {
        console.log('Request error:', error);
        // Handle the error
    });
}

function changeColor(e) {
    const listItem = this.parentNode;
    var formData = new FormData();
    formData.append('id', e.target.id);
    fetch('app/color.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if(data !== "error" ){
            listItem.classList ='';
            listItem.classList.add(`${data}`);
        }
    })
    .catch(error => {
        console.log('Request error:', error);
        // Handle the error
    });
}

const initSortableList = (e) => {
    e.preventDefault()
    const draggingItem = list.querySelector('.dragging');
    let siblings = [...list.querySelectorAll(".item:not(.dragging)")];

    let nextSibling = siblings.find(sibling => {
        return e.clientY <= sibling.offsetTop + sibling.offsetHeight / 2;
    });

    list.insertBefore(draggingItem, nextSibling);
    const updatedItems = list.querySelectorAll(".item");
    const updatedOrder = Array.from(updatedItems).map(item => item.id);

    let isDifferent;

    for(i = 0; i < updatedOrder.length; i++){
        if(curOrder[i] !== updatedOrder[i]) isDifferent = true
    }

    if(isDifferent) {
        var formData = new FormData();
        formData.append(`values`, updatedOrder.length);
        for(i = 0; i < updatedOrder.length; i++){
            formData.append(`${i}`, updatedOrder[i]);
        }
        fetch('app/drag.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if(data !== "error" ){
                console.log(data);
            }
        })
        .catch(error => {
            console.log('Request error:', error);
            // Handle the error
        });
    }
}

const handleSpan = (e) => {
    const span = e.target;
    const spanText = span.textContent;
    span.textContent = '';
    const input = document.createElement("input");
    input.setAttribute("type", "text");
    input.value = spanText.trim();
    span.appendChild(input)
    input.addEventListener('keypress', (e) => {handleEdit(e, input)});
}

const handleEdit = (e, input) => {
    if (e.key === 'Enter') {
      const span = e.target.parentNode;
      // Enter key is pressed
      e.preventDefault(); // Prevent form submission or other default behavior
      const inputValue = e.target.value;
      var formData = new FormData();
      formData.append('id', span.id);
      formData.append('text', inputValue)
      fetch('app/edit.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.text())
        .then(data => {
          if (data !== "error") {
            if (data) {
              console.log(data);
              span.removeChild(input);
              span.textContent = inputValue;
            }
          }
        })
        .catch(error => {
          console.log('Request error:', error);
          // Handle the error
        });
    }
  }

del.forEach(item => item.addEventListener("click", askForDoubleClick));
del.forEach(item => item.addEventListener("dblclick", deleteItem));
check.forEach(item => item.addEventListener("click", checkItem));
color.forEach(item => item.addEventListener("click", changeColor));
draggertabs.forEach(item => {
    const listItem = item.parentNode;
    item.addEventListener('dragstart', (e) => {
        listItem.classList.add('dragging');
    });
    item.addEventListener('dragend', (e) => {
        listItem.classList.remove('dragging');
    });

});
list.addEventListener("dragenter", e => e.preventDefault());
list.addEventListener('dragover', debounce(initSortableList));
spans.forEach(span => span.addEventListener('dblclick', handleSpan));