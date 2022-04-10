function getTerms() {
    try {
        const request = new XMLHttpRequest();
        request.open("GET", "ratings.php?action=getTerms", true);
        request.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let terms = document.getElementById("terms-select");
                
                let avail_terms = JSON.parse(request.responseText);

                // sort terms by date
                avail_terms.sort(function (term1, term2) {
                    return term1.split(' ')[1] > term2.split(' ')[1];
                });
       
                // add a dropdown menu item for each term
                avail_terms.forEach(term => {
                    let child = document.createElement('option');
                    child.value = term;
                    child.innerHTML = term;
                    terms.appendChild(child);
                })

            }
        }
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send(null);
    } catch (exception) {
        alert("Request failed. Please try again.");
    }
}

function getCourses() {
    // get selected term
    const terms = document.getElementById("terms-select");
    const term = terms.options[terms.selectedIndex].value;

    let courses = document.getElementById("courses-select");

    clearCourses(); // clear previous courses added to the dropdown menu
    clearTAs(); // clear previous TAs added to the dropdown menu

    // get courses for selected term
    try {
        const request = new XMLHttpRequest();
        request.open("GET", `ratings.php?action=getCourses&term=${term}`, true);
        request.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                
                console.warn(request.responseText);
                let avail_courses = JSON.parse(request.responseText);

                avail_courses.sort();
       
                // add a dropdown menu item for each course
                avail_courses.forEach(course => {
                    let child = document.createElement('option');
                    child.value = course;
                    child.innerHTML = course;
                    courses.appendChild(child);
                })
            }
        }
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send(null);
    } catch (exception) {
        alert("Request failed. Please try again.");
    }
}

function getTAs() {
    // get selected term and course
    const terms = document.getElementById("terms-select");
    const term = terms.options[terms.selectedIndex].value;
    const courses = document.getElementById("courses-select");
    const course = courses.options[courses.selectedIndex].value;

    const TAs_select = document.getElementById("TAs-select");

    clearTAs(); // clear previous TAs added to the dropdown menu

    // get TAs for selected term and course
    try {
        const request = new XMLHttpRequest();
        request.open("GET", `ratings.php?action=getTAs&term=${term}&course=${course}`, true);
        request.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let TAs = JSON.parse(request.responseText);
                TAs.sort();

                // add a dropdown menu item for each TA
                TAs.forEach(TA => {
                    let child = document.createElement('option');
                    child.value = TA;
                    child.innerHTML = TA;
                    TAs_select.appendChild(child);
                })
            }
        }
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send(null);
    } catch (exception) {
        alert("Request failed. Please try again.");
    }
}

/**
 * Clear previous courses added to the drop down menu
 */
function clearCourses() {
    let courses = document.getElementById("courses-select");

    while (courses.childNodes.length > 2) {
        courses.removeChild(courses.lastChild);
    }
}

/**
 * Clear previous TAs added to the drop down menu
 */
function clearTAs() {
    let TAs_select = document.getElementById("TAs-select");

    while (TAs_select.childNodes.length > 2) {
        TAs_select.removeChild(TAs_select.lastChild);
    }
}


// on load 
getTerms();
