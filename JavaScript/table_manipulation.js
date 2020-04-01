function addRowToTable() {
    var table = document.getElementById("table-movies");
    var row = table.insertRow(table.rows.length);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);

    //first cell
    var directorTextArea = document.createElement("TEXTAREA");
    directorTextArea.setAttribute("class", "text-area-movie")
    cell1.appendChild(directorTextArea)

    //second cell
    var titleTextArea = document.createElement("TEXTAREA");
    titleTextArea.setAttribute("class", "text-area-movie")
    cell2.appendChild(titleTextArea)

    //third cell
    var saveButton = createButton("Save", "button-row-save")
    saveButton.addEventListener("click", saveRow)
    cell3.appendChild(saveButton)

    var removeButton = createButton("Remove", "button-row-remove")
    removeButton.addEventListener("click", removeRow)
    cell3.appendChild(removeButton)
}

function createButton(btnText, btnClass) {
    var button = document.createElement("BUTTON");
    button.appendChild(document.createTextNode(btnText));
    button.setAttribute("class", btnClass);
    return button
}

function saveRow() {
    var row = this.parentNode.parentNode
    var firstCell = row.cells[0]
    var secondCell = row.cells[1]
    var thirdCell = row.cells[2]

    //first cell
    var textArea = firstCell.getElementsByClassName("text-area-movie")[0]
    var directorParagraph = document.createElement("P");
    directorParagraph.setAttribute("class", "paragraph-movie")
    directorParagraph.innerHTML = textArea.value
    firstCell.removeChild(textArea)
    firstCell.appendChild(directorParagraph)

    //second cell
    var textArea = secondCell.getElementsByClassName("text-area-movie")[0]
    var titleParagraph = document.createElement("P");
    titleParagraph.setAttribute("class", "paragraph-movie")
    titleParagraph.innerHTML = textArea.value
    secondCell.removeChild(textArea)
    secondCell.appendChild(titleParagraph)

    //third cell
    var button = thirdCell.getElementsByClassName("button-row-save")[0]
    button.textContent = "Edit"
    button.setAttribute("class", "button-row-edit")
    button.removeEventListener("click", saveRow); 
    button.addEventListener("click", editRow)
}

function editRow() {
    var row = this.parentNode.parentNode
    var firstCell = row.cells[0]
    var secondCell = row.cells[1]
    var thirdCell = row.cells[2]

    //first cell
    var paragraph = firstCell.getElementsByClassName("paragraph-movie")[0]
    var textArea = document.createElement("TEXTAREA");
    textArea.setAttribute("class", "text-area-movie")
    textArea.textContent = paragraph.textContent
    firstCell.removeChild(paragraph)
    firstCell.appendChild(textArea)

    //second cell
    var paragraph = secondCell.getElementsByClassName("paragraph-movie")[0]
    var textArea = document.createElement("TEXTAREA");
    textArea.setAttribute("class", "text-area-movie")
    textArea.textContent = paragraph.textContent
    secondCell.removeChild(paragraph)
    secondCell.appendChild(textArea)

    //third cell
    var button = thirdCell.getElementsByClassName("button-row-edit")[0]
    button.textContent = "Save"
    button.setAttribute("class", "button-row-save")
    button.removeEventListener("click", editRow); 
    button.addEventListener("click", saveRow)
}

function removeRow() {
    var index = this.parentNode.parentNode.rowIndex;
    var table = document.getElementById("table-movies");
    table.deleteRow(index);
}


document.getElementById("button-new-movie").addEventListener("click", addRowToTable);
