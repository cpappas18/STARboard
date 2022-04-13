function populateTable(request) {
    let table = document.getElementById("accounts-table");
    table.innerHTML = request.responseText;
}


function getAccounts() {
    try {
        const req = new XMLHttpRequest();
        req.open("GET", "get_users.php", true);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                populateTable(req);
            }
        }
        req.send(null);
    } catch (exception) {
        alert("Request failed. Please try again.");
    }
}

// function toggleTable() {
//     let table = document.getElementById("accounts-table");
//     let act = document.getElementById("edit-account-info");

//     if (table.style.display === "none") {
//         table.style.display = "block";
//         act.style.display = "none";
//     } else {
//         table.style.display = "none";
//         act.style.display = "block";
//     }
// }


function deleteAccount(username, student_id, ticket) {
    let del = confirm("Are you sure you want to delete this account?\nThis action cannot be undone.");

    if (del) {
        const req = new XMLHttpRequest();
        req.open("POST", "delete_user.php", true); // should be DELETE request but forbidden by McGill server
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                location.reload(); // refresh the page to show that the account was deleted
            }
        }
        req.send(`username=${username}&student_id=${student_id}&ticket=${ticket}`);
    }
}

function showAccount(username) {
    window.location.replace(`./edit_user.html?username=${username}`); 
}