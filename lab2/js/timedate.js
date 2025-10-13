function getTheDate() {
    toDays = new Date()
    theDate = '' + (toDays.getMonth() + 1) + " / " + toDays.getDate() + " / " + (toDays.getYear() - 100)
    document.getElementById("data").innerHTML = theDate
}

let timerID = null
let timerRunning = false

function stopClock() {
    if (timerRunning) {
        clearTimeout(timerID)
    }
    timerRunning = false
}

function startClock() {
    stopClock()
    getTheDate()
    showTime()
}

function showTime() {
    let now = new Date()
    let hours = now.getHours()
    let minutes = now.getMinutes()
    let seconds = now.getSeconds()
    let timeValue = '' + ((hours > 12) ? hours - 12 : hours)
    timeValue += ((minutes < 10) ? ':0' : ':') + minutes
    timeValue += ((seconds < 10) ? ':0' : ':') + seconds
    timeValue += (hours >= 12) ? ' P.M.' : ' A.M.'
    document.getElementById('zegarek').innerHTML = timeValue
    timerID = setTimeout('showTime()', 1000)
    timerRunning = true
    showDayOfWeek()
}

function showDayOfWeek() {
    let days = ['Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', "Niedziela"];
    let today = new Date();
    let dayName = days[today.getDay() - 1];
    let dayOfWeek = document.getElementById('dzienTygodnia');
    dayOfWeek.innerHTML = dayName;
}