// script.js
// wait for the dom
document.addEventListener("DOMContentLoaded", function () {
    
    // fetch api - fetch missions from target api
    function fetchMissions() {
        fetch('php/http.php?action=fetch', {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            populateTable(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // fetch api - get missions
     function getMissions() {
        fetch('php/http.php?action=get', {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            populateTable(data);
            
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // fetch api - delete mission by uuid
    function deleteMission(uuid) {
        fetch(`php/http.php?action=delete&uuid=${uuid}`, {
            method: 'DELETE',
        })
        .then(function() {
            getMissions();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // fetch api - delete all missions
    function deleteMissions() {
        fetch('php/http.php?action=delete', {
            method: 'DELETE',
        })
        .then(function() {
            getMissions();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // get missions on page load
    getMissions();

    // set uuid's
    var uuids = {};

    // dynamically populate table
    function populateTable(data) {
        var tableBody = document.querySelector("#missionTable tbody");
        tableBody.innerHTML = "";
        if (!data) {
            return;
        }
        var dataArray = Object.values(data);
        dataArray.forEach(function (item) {
            var row = tableBody.insertRow();
            var cells = [];
            for (var i = 0; i < 7; i++) {
                cells[i] = row.insertCell(i);
            }
            uuids[row.rowIndex] = item.uuid;
            cells[0].textContent = item.name;
            cells[1].textContent = item.id;
            cells[2].textContent = item.manufacturers.join(", ");
            cells[3].textContent = item.payload;
            cells[4].textContent = item.description;

            var deleteButton = document.createElement("button");
            deleteButton.textContent = "DELETE";
            deleteButton.classList.add("danger");

            var editButton = document.createElement("button");
            editButton.textContent = "EDIT";
            editButton.classList.add("primary");

            editButton.addEventListener("click", function () {
                toggleEdit(cells, row.rowIndex);
            });

            deleteButton.addEventListener("click", function () {
                deleteMission(item.uuid);
            });

            cells[5].appendChild(editButton);
            cells[6].appendChild(deleteButton);
        });
    }

    // set edit status
    var isEditing = false;

    // toggle edit mode
    function toggleEdit(cells, rowIndex) {
        cells.forEach(function (cell, index) {
            if (index !== 5 && index !== 6) {
                if (!isEditing) {
                    var currentText = cell.textContent;
                    var inputField = document.createElement("input");
                    inputField.type = "text";
                    inputField.value = currentText;
                    cell.textContent = "";
                    cell.appendChild(inputField);
                } else {
                    var inputField = cell.querySelector("input");
                    if (inputField) {
                        cell.textContent = inputField.value;
                    }
                }
            }
        });
        var editButton = cells[5].querySelector("button");
        if (editButton) {
            if (isEditing) {
                editButton.textContent = "EDIT";
            } else {
                editButton.textContent = "SAVE";
            }
        }
        if (isEditing) {
            saveChanges(cells, rowIndex);
        }
        isEditing = !isEditing;
    }

    // save changes
    function saveChanges(cells, rowIndex) {
        if (isEditing) {
            var updatedData = {};
            updatedData.uuid = uuids[rowIndex];
            updatedData.name = cells[0].textContent;
            updatedData.id = cells[1].textContent;
            updatedData.manufacturers = cells[2].textContent.split(", ");
            updatedData.payload = cells[3].textContent;
            updatedData.description = cells[4].textContent;
            if (validateInputFields(updatedData)) {
                fetch('php/http.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(updatedData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        getMissions();
                    } else if (Array.isArray(data.errors)) {
                        customAlert(data.errors.join('\n'));
                    } else {
                        console.error('Error')
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    }
    
    // handle section visibility
    function showSection(sectionId) {
        const sections = ['missions', 'imprint', 'about'];
        sections.forEach(function (section) {
            document.getElementById(section).style.display = 'none';
        });
        document.getElementById(sectionId).style.display = 'block';
    }

    // handle header navigation
    function addHeaderButtonEventListeners() {
        document.querySelector('a[href="#missions"]').addEventListener('click', function (event) {
            const dropdownMenu = document.querySelector('#dropdown-menu');
            dropdownMenu.style.display = 'none';   
            event.preventDefault();
            showSection('missions');
        });
        document.querySelector('nav.navigation-buttons a[href="#imprint"]').addEventListener('click', function (event) {
            event.preventDefault();
            showSection('imprint');
        });
        document.querySelector('nav.navigation-buttons a[href="#about"]').addEventListener('click', function (event) {
            event.preventDefault();
            showSection('about');
        });
    }

    // handle footer navigation
    function addFooterButtonEventListeners() {
        document.querySelectorAll('footer nav a').forEach(function (footerButton) {
            footerButton.addEventListener('click', function (event) {
                event.preventDefault();
                const sectionId = footerButton.getAttribute('href').substring(1);
                showSection(sectionId);
            });
        });
    }

    // handle burger menu navigation
    function addDropwDownButtonEventListeners() {
        const dropdownMenu = document.querySelector('#dropdown-menu');
        document.querySelector('#dropdown-menu a[href="#imprint"]').addEventListener('click', function (event) {
            event.preventDefault();
            showSection('imprint');
            dropdownMenu.style.display = 'none';
        });
        document.querySelector('#dropdown-menu a[href="#about"]').addEventListener('click', function (event) {
            event.preventDefault();
            showSection('about');
            dropdownMenu.style.display = 'none';
        });
    }

    // initially show missions section
    showSection('missions');

    // add header event listeners
    addHeaderButtonEventListeners();
    addDropwDownButtonEventListeners();
    addFooterButtonEventListeners();

    // add action button event listeners
    document.getElementById("fetchData").addEventListener("click", function () {
        fetchMissions();
    });

    document.getElementById("deleteData").addEventListener("click", function () {
        deleteMissions();
    });

    document.getElementById("printPDF").addEventListener("click", printPDF);

    document.getElementById("addItem").addEventListener("click", function () {
        addNewItem();
    });

    // toggle burger menu dropdown
    document.getElementById("burger-menu").addEventListener("click", function () {
        const dropdownMenu = document.querySelector('#dropdown-menu');
        dropdownMenu.style.display = (dropdownMenu.style.display === 'block') ? 'none' : 'block';
    });

    // load default print view
    function printPDF() {
        window.print();
    }
    
    // validate input fields
    function validateInputFields(obj) {
        var isValid = true;
        var name = obj.name ? obj.name.trim() : '';
        var id = obj.id ? obj.id.trim() : '';
        var manufacturers = obj.manufacturers;
        var payload = obj.payload ? obj.payload.trim() : '';
        var description = obj.description ? obj.description.trim() : '';
        if (name === "" || id === "" || payload === "" || description === "") {
            customAlert("All fields must be filled out.");
            getMissions();
            isValid = false;
        }
        if (id !== "" && !/^[0-9a-zA-Z]{7}$/.test(id)) {
            customAlert("Mission ID must be 7 alphanumeric characters.");
            getMissions();
            isValid = false;
        }
        if (manufacturers.some(manufacturer => manufacturer.trim() === "")) {
            customAlert("Manufacturers array should not contain empty strings.");
            getMissions();
            isValid = false;
        }
        return isValid;
    }

    // add new mission item
    function addNewItem() {
        var tableBody = document.querySelector("#missionTable tbody");
        var newRow = tableBody.insertRow();
        var cells = [];
        for (var i = 0; i < 7; i++) {
            cells[i] = newRow.insertCell(i);
            if (i !== 5 && i !== 6) {
                var inputField = document.createElement("input");
                inputField.type = "text";
                inputField.value = "";
                cells[i].appendChild(inputField);
            }
        }
        var saveButton = document.createElement("button");
        saveButton.textContent = "SAVE";
        saveButton.classList.add("primary");

        var deleteButton = document.createElement("button");
        deleteButton.textContent = "DELETE";
        deleteButton.classList.add("danger");

        saveButton.addEventListener("click", function () {
            saveNewMission(cells);
        });
        deleteButton.addEventListener("click", function () {
            if (newRow) {
                tableBody.deleteRow(newRow.rowIndex - 1);
            }
        });
        cells[5].appendChild(saveButton);
        cells[6].appendChild(deleteButton);
    }

    // save new mission item
    function saveNewMission(cells) {
        var newMission = {};
        newMission.uuid = generateUUID();
        newMission.name = cells[0].querySelector("input").value;
        newMission.id = cells[1].querySelector("input").value;
        newMission.manufacturers = cells[2].querySelector("input").value.split(", ");
        newMission.payload = cells[3].querySelector("input").value;
        newMission.description = cells[4].querySelector("input").value;
        if (validateInputFields(newMission)) {
            fetch('php/http.php?action=create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(newMission),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    getMissions();
                } else if (Array.isArray(data.errors)) {
                    customAlert(data.errors.join('\n'));
                } else {
                    console.error('Error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
            cells.forEach(function (cell, index) {
                if (index !== 5 && index !== 6) {
                    var textContent = document.createTextNode(cell.querySelector("input").value);
                    cell.innerHTML = "";
                    cell.appendChild(textContent);
                }
            });
            var editButton = cells[5].querySelector("button");
            if (editButton) {
                editButton.textContent = "EDIT";
            }
        }
    }

    // generate uuid for new mission item
    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    // replace the default alert function 
    function customAlert(message) {
        var modal = document.createElement('div');
        modal.classList.add('custom-alert-modal');
    
        var alertBox = document.createElement('div');
        alertBox.classList.add('custom-alert-box');
    
        var heading = document.createElement('h2');
        heading.textContent = 'ERROR';
        alertBox.appendChild(heading);
    
        var messageElement = document.createElement('p');
        messageElement.textContent = message;
        alertBox.appendChild(messageElement);
    
        var okButton = document.createElement('button');
        okButton.textContent = 'OK';
        okButton.style.marginLeft = 'auto';
        okButton.addEventListener('click', function() {
            document.body.removeChild(modal);
        });
        alertBox.appendChild(okButton);
        modal.appendChild(alertBox);
        document.body.appendChild(modal);
    }
    
});