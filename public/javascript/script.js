const humburgerMenuBtn = document.querySelector(".humburger-menu");
const sideNavBar = document.querySelector(".side-navbar");
const sideNavbarLinks = document.querySelectorAll(".side-navbar a");
const createReminderBtn = document.querySelector(".create-reminder-btn")
let user_id;
const pageHost=window.location.origin;
//side nave bar toggle
humburgerMenuBtn.addEventListener("click", function () {
    sideNavBar.classList.toggle("hidden");
});
sideNavBar.addEventListener("click", function (e) {
    let y = [...sideNavbarLinks]
    if (!y.some(d => d == e.target)) {
        sideNavBar.classList.toggle("hidden");
    }
});



if (window.location.pathname == "/reminder/Reminders/index") {
    user_id = document.querySelector("#user_id").value;
    fetchReminders();
    datePicker();
    document.querySelector(".create-reminder .create-reminder-btn").addEventListener("click",createReminder)

}



async function fetchReminders() {
    const remindersTableBigScreen = document.querySelector("#mytable_big-screen");
    const remindersTableSmallScreen = document.querySelector(".reminders-list-small-screen-list");
    const rawResponse = await fetch(`${pageHost}/reminder/Reminders/list?user_id=${user_id}`);
    const reminders = await rawResponse.json();
    remindersTableBigScreen.innerHTML=remindersTableSmallScreen.innerHTML="";
    reminders.forEach(reminder => {
        remindersTableBigScreen.insertAdjacentHTML("beforeend",
            `<tr id="reminder-id_${reminder.reminder_id}">
       <td class="title">${reminder.title}</td>
       <td class="date">${reminder.date}</td>
       <td class="remind_before">${reminder.remind_before}</td>
       <td>
           <button type="button" id="editBtn" onclick="edit(${reminder.reminder_id})" class="btn btn-outline-secondary">Edit</button>
           <button type="button" id="DeleteBtn" onclick="deleteReminder(${reminder.reminder_id})" class="btn btn-outline-danger">Delete</button>
            </td>
        </tr>`);



        remindersTableSmallScreen.insertAdjacentHTML("beforeend",
            `<div class="reminder-cell">
            <div id="reminder-id_${reminder.reminder_id}" class="table-header table-row">
                <p class="table-th title">${reminder.title}</p>
                <i class="fa-solid fa-arrow-down"></i>
            </div>
            <div class="table-body">
                <div class="table-row">
                    <p class="table-td date">${reminder.date}</p>
                </div>
                <div class="table-row">
                    <p class="table-td remind_before">Reminder before: ${reminder.remind_before}</p>
                </div>
                <div class="table-row">
                    <button type="button" id="editBtn"  onclick="edit(${reminder.reminder_id})"" class="btn btn-outline-secondary">Edit</button>
                    <button type="button" id="DeleteBtn" onclick="deleteReminder(${reminder.reminder_id})" class="btn btn-outline-danger">Delete</button>
                </div>
            </div>
        </div>`);
    });
    smallScreenRemindersDropDown();
}


function datePicker(){
    const datePicker = document.querySelector("#date-picker");
    const reminder_date = document.querySelector(".create-reminder #date");
    const updateDatePicker = document.querySelector(".update-reminder #update_date-picker");
    const update_reminder_date = document.querySelector(".update-reminder #update_date");
   datePicker.addEventListener("change",function(){
    reminder_date.value = datePicker.value.slice(5,);
    }) 
    updateDatePicker.addEventListener("change",function(){
        update_reminder_date.value = updateDatePicker.value.slice(5,);
    }) 
}


async function createReminder(e){
    e.preventDefault();
    let date = document.querySelector('.create-reminder #date');
    let title = document.querySelector('.create-reminder #title');
    let remind_before = document.querySelector('.create-reminder #remind_before');
    const alertsBox = document.querySelector(".alertsbox");
    const res = await fetch(`${pageHost}/reminder/Reminders/create?user_id=${user_id}&date=${date.value}&title=${title.value}&remind_before=${remind_before.value}`);
    const result = await res.json();
    alertsBox.innerHTML="";
    if(result.successful){
        alertsBox.insertAdjacentHTML("beforeend",
        `<div class="ok-alert">Reminder added.</div>`);
        fetchReminders();
        date.value=title.value=remind_before.value=""; 

    }else{
        result.Error.forEach(err=>{alertsBox.insertAdjacentHTML("beforeend",
        `<div class="not-ok-alert">${err}</div>`)});
    }

}

function edit(reminder_id){
    const title = document.querySelector(`#reminder-id_${reminder_id} .title`);
    const date = document.querySelector(`#reminder-id_${reminder_id} .date`);
    const remind_before = document.querySelector(`#reminder-id_${reminder_id} .remind_before`);
    const update_title = document.querySelector(`#update_title`);
    const update_date = document.querySelector(`#update_date`);
    const update_remind_before = document.querySelector(`#update_remind_before`);
    const updateReminderContainer = document.querySelector(".update-reminder");
    const updateReminderBtn = document.querySelector(".update-reminder .update-reminder-btn");
    const alertsBox = document.querySelector(".create-reminder .alertsbox");
    const updateAlertBox = document.querySelector(".update-reminder .alertsbox")
    const closeUpdateReminderBtn = document.querySelector(".update-reminder .return-update-reminder-btn");
    update_title.value= title.textContent;
    update_date.value = date.textContent;
    update_remind_before.value= remind_before.textContent;
    updateReminderContainer.style.height ="auto";

    closeUpdateReminderBtn.addEventListener("click",function(e){
        e.preventDefault();
        updateReminderContainer.style.height ="0px";
        updateReminderBtn.removeEventListener("click",applyChanges);
    });

    updateReminderBtn.addEventListener("click", applyChanges);
    
    async function applyChanges (e){
        e.preventDefault();
        const new_title = update_title.value;
        const new_date = update_date.value;
        const new_remind_before = update_remind_before.value;
        const url = `${pageHost}/reminder/Reminders/update?user_id=${user_id}&reminder_id=${reminder_id}&date=${new_date}&title=${new_title }&remind_before=${new_remind_before}`
        console.log(url);
        const res = await fetch(url);
        const result = await res.json();
        if(result.successful){
            alertsBox.innerHTML="";
            alertsBox.insertAdjacentHTML("beforeend",
            `<div class="ok-alert">Reminder Updated!</div>`);
            window.scrollTo({ top: 100 });
            updateReminderContainer.style.height ="0px";
            updateReminderBtn.removeEventListener("click",applyChanges);
            fetchReminders();
        }else{
            updateAlertBox.innerHTML="";
            result.Error.forEach(err=>{updateAlertBox.insertAdjacentHTML("beforeend",
            `<div class="not-ok-alert">${err}</div>`)});
        }
    
    }
}

async function deleteReminder(remider_id){
        const deleteReminderContainer = document.querySelector(".delete-reminder");
        const deleteReminderBtn = document.querySelector(".delete-reminder-btn");
        const reutrnToRemindersBtn = document.querySelector(".return-delete-reminder-btn");
        const alertsBox = document.querySelector(".create-reminder .alertsbox");
        deleteReminderContainer.style.height = "100vh";

        reutrnToRemindersBtn.addEventListener("click",function(e){
            e.preventDefault();
            deleteReminderContainer.style.height ="0px"; 
            deleteReminderBtn.removeEventListener("click",applyDelete);
        });

        deleteReminderBtn.addEventListener("click",applyDelete);
        async function applyDelete(e){
            e.preventDefault();
            const res = await fetch(`${pageHost}/reminder/Reminders/delete?user_id=${user_id}&reminder_id=${remider_id}`);
            const result = await res.json();
            window.scrollTo({ top: 100 });
            deleteReminderContainer.style.height ="0px";
            alertsBox.innerHTML="";
            if(result.successful){
                alertsBox.insertAdjacentHTML("beforeend",
                `<div class="ok-alert">Reminder Deleted!</div>`);
                deleteReminderBtn.removeEventListener("click",applyDelete);
                fetchReminders();
            }else{
                alertsBox.insertAdjacentHTML("beforeend",
                `<div class="not-ok-alert">Something went wrong!</div>`);
            }
        }

}


//small screen remiders drop down
function smallScreenRemindersDropDown() {
    const tableDropDownIcon = document.querySelectorAll(".fa-arrow-down");
    const tableDropDownBtn = document.querySelectorAll(".reminder-cell");
    const tableBodySmallScreen = document.querySelectorAll(".reminders-list-small-screen .table-body")
    tableDropDownBtn.forEach(function (btn, i) {
        btn.addEventListener("click", function (e) {
            if (tableDropDownIcon[i].classList.contains("rotated")) {
                tableBodySmallScreen[i].style.height = "0px";
                tableDropDownIcon[i].style.transform = "rotate(0deg)";
                tableDropDownIcon[i].classList.remove("rotated");
            } else {
                tableBodySmallScreen[i].style.height = "auto";
                tableDropDownIcon[i].style.transform = "rotate(180deg)";
                tableDropDownIcon[i].classList.add("rotated");
            }
        });
    });
}

