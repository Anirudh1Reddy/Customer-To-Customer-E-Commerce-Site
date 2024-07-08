var modal = document.getElementById("addPModal");
var btn1 = document.getElementById("myBtn1");
var span = document.getElementsByClassName("close")[0];
var table = document.querySelector(".order_table");

btn1.onclick = function() {
    modal.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

document.querySelector("form").addEventListener("submit", function(event) {
    event.preventDefault();

    if (!this.checkValidity()) {
        alert("Please fill in all of the fields.");
        return;
    }

    let formData = new FormData(this);

    fetch('insert_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            return response.text();
        }
    })
    .then(data => {
        if (typeof data === 'string') {
            console.log('Response:', data);
        } else {
            console.log('Parsed Data:', data);
        }

        this.reset();
        modal.style.display = "none";
    })
    .catch(error => {
        console.error('Error:', error);
    });

    let name = document.getElementById('nameP').value;
    let description = document.getElementById('descriptionP').value;
    let imageUrl = document.getElementById('image_url').value;
    let price = document.getElementById('priceP').value;
    let deliveryOpt = document.querySelector('input[name="delivery_opt"]:checked').value;
    let category = document.querySelector('input[name="category_tag"]:checked').value;

    let newRow = table.insertRow(-1); // appends row to end of table
    let nameCell = newRow.insertCell(0);
    let descriptionCell = newRow.insertCell(1);
    let imageUrlCell = newRow.insertCell(2);
    let priceCell = newRow.insertCell(3);
    let deliveryOptCell = newRow.insertCell(4);
    let categoryCell = newRow.insertCell(5);

    nameCell.textContent = name;
    descriptionCell.textContent = description;
    imageUrlCell.textContent = imageUrl;
    priceCell.textContent = price;
    deliveryOptCell.textContent = deliveryOpt;
    categoryCell.textContent = category;

    var deleteCell = document.createElement('td');
    var deleteBtn = document.createElement('button');
    deleteBtn.className = 'delete_btn';
    deleteBtn.textContent = 'Delete';
    deleteCell.appendChild(deleteBtn);
    newRow.appendChild(deleteCell);

    document.getElementById('nameP').value = '';
    document.getElementById('descriptionP').value = '';
    document.getElementById('image_url').value = '';
    document.getElementById('priceP').value = '';
    document.querySelector('input[name="delivery_opt"]:checked').checked = false;
    document.querySelector('input[name="category_tag"]:checked').checked = false;

    modal.style.display = "none";

    deleteBtn.onclick = function() {
        newRow.parentNode.removeChild(newRow);
    }
});

document.querySelectorAll('.delete_btn').forEach(button => {
    button.addEventListener('click', function(event) {
        let row = this.closest('tr');
        let name = row.cells[0].textContent;

        console.log('Deleting product:', name);

        fetch('delete_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => {
            console.log('Response:', response);
            if (!response.ok) {
                throw new Error('Failed to delete product');
            }
            return response.json(); 
        })
        .then(data => {
            console.log(data);
            if (data.success) {
                row.parentNode.removeChild(row);
            } else {
                alert('Failed to delete product.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});