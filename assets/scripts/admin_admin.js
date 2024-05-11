const resultRows = document.querySelectorAll("tr");
const editBtns = document.querySelectorAll(".edit-button");
const deleteBtns = document.querySelectorAll(".delete-button");
const table = document.querySelector("table");

resultRows.forEach(row => 
    row.addEventListener("click", editOrDelete)  
);

if(table)
{
    table.addEventListener("click", collapseForm);
}

function collapseForm(evt){
    if(evt.target.className.includes("btn-close")){
        const collapseRow = evt.target.parentElement.parentElement.parentElement.parentElement;

        // enable the edit button
        const editBtn = collapseRow.previousElementSibling.children[6].children[0];
        editBtn.disabled = false;
        editBtn.classList.remove("disabled");

        // Collapse the row
        collapseRow.remove();
    }
}

function editOrDelete(evt){
    
    if(evt.target.className.includes("edit-button"))
    {
        // Disable the button
        evt.target.disabled = true;
        evt.target.classList.add("disabled");

        const editRow = document.createElement("tr");
        editRow.innerHTML = `
        <td colspan="7">
            <form class="editRouteForm d-flex justify-content-between" action="${evt.target.dataset.link}" method="POST">
                <input type="hidden" name="id" value="${evt.target.dataset.id}">
                <input type="text" class="form-control" name="fullname" value="${evt.target.dataset.fullname}" placeholder="Fullname" required>
                <input type="text" class="form-control" name="username"value="${evt.target.dataset.username}"  placeholder="Username" required>
                <input type="date" class="form-control" name="birth" value="${evt.target.dataset.birth}" placeholder="Birthday">
                <input type="text" class="form-control" name="address" value="${evt.target.dataset.address}" placeholder="Address">
                <input type="text" class="form-control" name="phone" value="${evt.target.dataset.phone}"  placeholder="Phone">

                <button type="submit" class="btn btn-success btn-sm" name="edit">SUBMIT</button>
                <button type="button" class="btn-close align-self-center"></button>
            </form>
        </td>
    `;
    
    this.after(editRow);
    }

    // if delete button is clicked
    else if(evt.target.className.includes("delete-button"))
    {
        const deleteInput = document.querySelector("#delete-id");
        deleteInput.value = evt.target.dataset.id;
    }
}
