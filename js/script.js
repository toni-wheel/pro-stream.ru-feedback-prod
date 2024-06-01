const TIME_STEP_MINUTES = 30;
const START_HOUR = 0;
const END_HOUR = 23;
const MINUTES_PER_DAY = 1440;

const API_URL = "https://lsoft.space/fateechev/feedback/php/send_mail.php";
const REDIRECT_URL = "https://pro-stream.ru/spasibo";

const responseData =
  "25 (12:00)-(14:00)<br> 25 (15:00)-(15:30)<br> 25 (16:30)-(19:00)<br> 26 (20:00)-(22:00)<br> 27 (10:00)-(12:30)<br> 27 (15:30)-(19:00)<br> 28 (08:30)-(12:00)<br> 04 (09:00)-(14:30)<br> 06 (09:00)-(12:00)<br> 06 (12:00)-(15:30)<br> 07 (09:30)-(13:00)<br> 07 (15:00)-(19:00)<br> 09 (19:00)-(22:00)<br> 10 (15:30)-(19:00)<br> 18 (12:00)-(16:00)<br> 19 (13:00)-(17:00)<br> 02 (17:00)-(22:00)<br> 03 (17:00)-(22:00)<br>";

function createTimeArray(startHour, endHour, timeStep) {
  let times = [];
  for (let hour = startHour; hour <= endHour; hour++) {
    for (let minute = 0; minute < 60; minute += timeStep) {
      times.push(
        `${hour.toString().padStart(2, "0")}:${minute
          .toString()
          .padStart(2, "0")}`
      );
    }
  }
  return times;
}

let timeArray = createTimeArray(START_HOUR, END_HOUR, TIME_STEP_MINUTES);

const bookingForm = document.querySelector("#booking-form");
const zal_arenda = document.querySelector("#zal");
const counterNum = document.querySelector("#counter-num");
const counterMinus = document.querySelector("#counter-minus");
const counterPlus = document.querySelector("#counter-plus");

const timeContainer = document.querySelector("#time-container");
const endTimeText = document.querySelector("#end-time-text");
const freeTimeText = document.querySelector("#free-time-text");

const formSend = document.querySelector("#form-send");

const nameInput = document.getElementById("name");
const phoneInput = document.getElementById("phone");
// const radioInputs = document.querySelectorAll(
//   'input[type="radio"][name="main-service"]'
// );
const checkboxInputs = document.querySelectorAll(
  'input[type="checkbox"][name="add-services"]'
);
const personalData = document.querySelector("#personal-data");

Date.prototype.toCustomDate = function () {
  var year = this.getFullYear();
  var month = this.getMonth() + 1;
  var day = this.getDate();

  if (month < 10) {
    month = "0" + month;
  }
  if (day < 10) {
    day = "0" + day;
  }

  return year + "-" + month + "-" + day;
};

Date.prototype.toDefaultDate = function () {
  var day = this.getDate();
  var month = this.getMonth() + 1;
  var year = this.getFullYear();

  if (day < 10) {
    day = "0" + day;
  }
  if (month < 10) {
    month = "0" + month;
  }

  return day + "." + month + "." + year;
};

Date.prototype.dateOperations = function (days, operation) {
  if (typeof days !== "number") {
    throw new Error("Количество дней должно быть числом.");
  }

  if (operation !== "+" && operation !== "-") {
    throw new Error("Операция должна быть '+' или '-'.");
  }

  var result = new Date(this);

  if (operation === "+") {
    result.setDate(result.getDate() + days);
  } else if (operation === "-") {
    result.setDate(result.getDate() - days);
  }

  return result;
};

String.prototype.toDateObjectFromString = function () {
  const parts = this.split("-");
  if (parts.length === 3) {
    var year = parseInt(parts[0]);
    var month = parseInt(parts[1]) - 1;
    var day = parseInt(parts[2]);
    return new Date(year, month, day);
  } else {
    return new Date(0);
  }
};

Date.prototype.toCustomTime = function () {
  var hours = this.getHours();
  var minutes = this.getMinutes();

  if (hours < 10) {
    hours = "0" + hours;
  }
  if (minutes < 10) {
    minutes = "0" + minutes;
  }

  return hours + ":" + minutes;
};

Date.prototype.roundUpTime = function () {
  var hours = this.getHours();
  var minutes = this.getMinutes();

  if (minutes > 0) {
    hours++;
    minutes = 0;
  }

  if (hours < 10) {
    hours = "0" + hours;
  }

  return hours + ":00";
};

String.prototype.toTimeObjectFromString = function (name) {
  const parts = this.split(":");
  if (parts.length === 2) {
    var hours = parseInt(parts[0]);
    var minutes = parseInt(parts[1]);
    return new Date(
      myBookingForm[name].getFullYear(),
      myBookingForm[name].getMonth(),
      myBookingForm[name].getDate(),
      hours,
      minutes
    );
  } else {
    return new Date(0);
  }
};

class Time {
  constructor(timeString) {
    const [hours, minutes] = timeString.split(":").map(Number);
    this.hours = hours;
    this.minutes = minutes;
  }

  setTime(hours, minutes) {
    this.hours = Number(hours);
    this.minutes = Number(minutes);
    if (this.minutes >= 60) {
      this.hours += Math.floor(this.minutes / 60);
      this.minutes = this.minutes % 60;
    }
  }

  addTime(hours, minutes) {
    this.hours += Number(hours);
    this.minutes += Number(minutes);
    if (this.minutes >= 60) {
      this.hours += Math.floor(this.minutes / 60);
      this.minutes = this.minutes % 60;
    }
  }

  subtractTime(hours, minutes) {
    this.hours -= Number(hours);
    this.minutes -= Number(minutes);
    if (this.minutes < 0) {
      this.hours -= Math.ceil(Math.abs(this.minutes) / 60);
      this.minutes = 60 - (Math.abs(this.minutes) % 60);
    }
  }

  compare(otherTime) {
    if (!(otherTime instanceof Time)) {
      otherTime = new Time(otherTime);
    }

    if (this.hours === otherTime.hours && this.minutes === otherTime.minutes) {
      return 0;
    } else if (
      this.hours > otherTime.hours ||
      (this.hours === otherTime.hours && this.minutes > otherTime.minutes)
    ) {
      return 1;
    } else {
      return -1;
    }
  }

  toString() {
    const paddedHours = this.hours < 10 ? "0" + this.hours : this.hours;
    const paddedMinutes = this.minutes < 10 ? "0" + this.minutes : this.minutes;
    return `${paddedHours}:${paddedMinutes}`;
  }

  resetTime() {
    this.hours = 0;
    this.minutes = 0;
  }

  totalMinutes() {
    return this.hours * 60 + this.minutes;
  }
}

const now = new Date();

class BookingForm {
  constructor() {
    this.zal = "";
    this.name = "";
    this.phone = "";
    this.mainService = "rent";
    this.addService = [];
    this.dateTimeStart = new Date();
    this.dateTimeEnd = new Date();
    this.dateTimeTotal = new Date();
    this.freeTime = new Time("00:00");
    this.rentalHours = "01:00";
    this.dataPolicyConsent = true;
    this.init();
  }

  getDate(name) {
    return this[name].toCustomDate();
  }

  setDate(name, value) {
    this[name] = value.toDateObjectFromString();
    if (name === "dateTimeEnd") {
      this.updateTotalTime();
    }
  }

  getTime(name) {
    return this[name].toCustomTime();
  }

  setTime(name, value) {
    this[name] = value.toTimeObjectFromString(name);
    if (name === "dateTimeEnd") {
      this.updateTotalTime();
    }
  }

  setMainService(value) {
    this.mainService = value;
    this.addFreeTime(value);
    if (value !== "rent" && this.addService[0] === "add-camera") {
      this.addFreeTime("add-camera");
    }
  }

  unsetMainService(value) {
    this.mainService = value;
    this.subtractFreeTime(value);
    if (value !== "rent" && this.addService[0] === "add-camera") {
      this.subtractFreeTime("add-camera");
    }
  }

  setAddService(value) {
    this.addService.push(value);
    if (this.mainService !== "rent") {
      this.addFreeTime(value);
    }
  }

  unsetAddService(value) {
    const index = this.addService.indexOf(value);
    if (index !== -1) {
      this.addService.splice(index, 1);
      if (this.mainService !== "rent") {
        this.subtractFreeTime(value);
      }
    }
  }

  setRentalHours(value) {
    this.rentalHours = new Time(value).totalMinutes();
  }

  addFreeTime(value) {
    if (value === "rent-shooting") {
      this.freeTime.addTime(0, 30);
    } else if (value === "rent-shooting-broadcast") {
      this.freeTime.addTime(1, 0);
    } else if (value === "add-camera") {
      this.freeTime.addTime(0, 30);
    }
    this.updateTotalTime();
  }

  subtractFreeTime(value) {
    if (value === "rent-shooting") {
      this.freeTime.subtractTime(0, 30);
    } else if (value === "rent-shooting-broadcast") {
      this.freeTime.subtractTime(1, 0);
    } else if (value === "add-camera") {
      this.freeTime.subtractTime(0, 30);
    }
    this.updateTotalTime();
  }

  updateTotalTime() {
    let newTotalTime = new Time(this.dateTimeEnd.toCustomTime());
    newTotalTime.addTime(this.freeTime.hours, this.freeTime.minutes);
    this.setTime("dateTimeTotal", newTotalTime.toString());
  }

  init() {
    renderTimeButton(this.dateTimeStart.toCustomDate(), true);
    const nextDate = this.dateTimeEnd.dateOperations(1, "+");
    renderTimeButton(nextDate.toCustomDate(), false);
  }
}

const myBookingForm = new BookingForm();
myBookingForm.zal = zal_arenda.value;
nameInput.addEventListener("input", function () {
  myBookingForm.name = this.value;
});

phoneInput.addEventListener("input", function () {
  myBookingForm.phone = this.value;
});

// let previousRadioButton = null;

// radioInputs.forEach((input) => {
//   input.addEventListener("change", function () {
//     if (previousRadioButton !== null) {
//       myBookingForm.unsetMainService(previousRadioButton.value);
//     }
//     if (this.checked) {
//       myBookingForm.setMainService(this.value);
//       previousRadioButton = this;
//     }

//     if (myBookingForm.freeTime.toString() !== "00:00") {
//       freeTimeText.textContent =
//         "(+" + myBookingForm.freeTime.totalMinutes() + " минут бесплатно)";
//     } else {
//       freeTimeText.textContent = "";
//     }
//   });
// });

// Находим все элементы input с атрибутом checked в списке
const checkedInput = document.querySelector(
  'input[name="main-service"]:checked'
);

// Проверяем, что элемент найден
if (checkedInput) {
  // Получаем значение атрибута value выбранного элемента input
  const checkedValue = checkedInput.value;
  myBookingForm.setMainService(checkedValue);
}

checkboxInputs.forEach((input) => {
  input.addEventListener("change", function () {
    if (this.checked) {
      myBookingForm.setAddService(this.value);
    } else {
      myBookingForm.unsetAddService(this.value);
    }

    if (myBookingForm.freeTime.toString() !== "00:00") {
      freeTimeText.textContent =
        "(+" + myBookingForm.freeTime.totalMinutes() + " минут бесплатно)";
    } else {
      freeTimeText.textContent = "";
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const options = {
    actions: {
      clickDay(e, self) {
        if (self.selectedDates[0]) {
          timeContainer.innerHTML = "";
          myBookingForm.setDate("dateTimeStart", self.selectedDates[0]);
          myBookingForm.setDate("dateTimeEnd", self.selectedDates[0]);
          renderTimeButton(self.selectedDates[0], true);

          const nextDate = myBookingForm.dateTimeEnd.dateOperations(1, "+");
          renderTimeButton(nextDate.toCustomDate(), false);

          counterNum.value = "01:00";
          counterNum.dataset.increment = 2;
          endTimeText.textContent = "";

          if (myBookingForm.freeTime.toString() !== "00:00") {
            freeTimeText.textContent =
              "(+" +
              myBookingForm.freeTime.totalMinutes() +
              " минут бесплатно)";
          } else {
            freeTimeText.textContent = "";
          }
        } else {
          timeContainer.textContent = "";
        }
      },
    },
    settings: {
      lang: "ru",
      selected: {
        dates: [myBookingForm.dateTimeStart.toCustomDate()],
      },
      range: {
        disableAllDays: true,
        enabled: [`${myBookingForm.dateTimeStart.toCustomDate()}:3000-01-01`],
      },
    },
  };
  const calendar = new VanillaCalendar("#calendar", options);
  calendar.init();
});

async function sendRequest() {
  const proxyUrl = "https://cors-anywhere.herokuapp.com/";
  const targetUrl =
    "https://lsoft.space/fateechev/cal_events.php?zal=" + zal_arenda.value;
  try {
    const response = await fetch(targetUrl);
    if (!response.ok) {
      throw new Error(
        "Произошла ошибка при выполнении запроса: " + response.status
      );
    }
    return await response.text();
  } catch (error) {
    console.error("Ошибка при отправке запроса:", error);
    return;
  }
}

function getNextSiblingByIndex(element, index) {
  let nextElement = element;
  for (let i = 0; i < index; i++) {
    nextElement = nextElement.nextElementSibling;
    if (!nextElement) {
      return null;
    }
  }
  return nextElement;
}

function getFreeTime(timeArray, scheduledTime) {
  let freeTime = [];
  let busyTime = [];

  for (let interval of scheduledTime) {
    let start =
      parseInt(interval[0].split(":")[0]) * 60 +
      parseInt(interval[0].split(":")[1]);
    let end =
      parseInt(interval[1].split(":")[0]) * 60 +
      parseInt(interval[1].split(":")[1]);
    for (let i = start; i < end; i++) {
      busyTime.push(i);
    }
  }

  for (let time of timeArray) {
    let minutes =
      parseInt(time.split(":")[0]) * 60 + parseInt(time.split(":")[1]);
    if (!busyTime.includes(minutes)) {
      freeTime.push(time);
    }
  }

  return freeTime;
}

function removeSpacesAndBreaks(str) {
  const stringWithoutSpaces = str.replace(/\s+/g, "");
  const stringWithoutBreaks = stringWithoutSpaces.replace(/<br>/gi, "");
  return stringWithoutBreaks;
}

function parseScheduleString(scheduleString) {
  const pattern = /(\d{2})\((\d{2}:\d{2})\)-\((\d{2}:\d{2})\)/g;
  const scheduleArray = [];

  let match;
  while ((match = pattern.exec(scheduleString)) !== null) {
    const element = {
      day: parseInt(match[1]),
      start: match[2],
      end: match[3],
    };
    scheduleArray.push(element);
  }

  return scheduleArray;
}

function findLastIndex(array, condition) {
  for (let i = array.length - 1; i >= 0; i--) {
    if (condition(array[i])) {
      return i;
    }
  }
  return -1;
}

function getPreviousElement(element, steps) {
  var currentElement = element;
  for (var i = 0; i < steps; i++) {
    if (currentElement.previousElementSibling) {
      currentElement = currentElement.previousElementSibling;
    } else {
      return null;
    }
  }
  return currentElement;
}

function splitByMonths(inputString) {
  const lines = inputString.split("<br>");

  const result = [];
  let currentMonthLines = lines.slice();

  for (let i = 0; i < lines.length - 1; i++) {
    const currentDate = parseInt(lines[i].split(" ")[0]);
    const nextDate = parseInt(lines[i + 1].split(" ")[0]);

    if (currentDate > nextDate) {
      result.push(currentMonthLines.join("<br>"));
      currentMonthLines = lines.slice(i + 1);
    }
  }

  result.push(currentMonthLines.join("<br>"));

  return result;
}

async function renderTimeButton(selectedDate, isVisible) {
  let selectedDay = new Date(selectedDate).getDate();
  let selectedMonth = new Date(selectedDate).getMonth();

  let scheduledSlotsByMonth = await sendRequest();
  // let scheduledSlotsByMonth = responseData;
  scheduledSlotsByMonth = splitByMonths(scheduledSlotsByMonth);
  let monthIndex = selectedMonth - now.getMonth();

  let scheduledSlots = "";

  if (monthIndex < scheduledSlotsByMonth.length) {
    scheduledSlots = scheduledSlotsByMonth[monthIndex];
  }

  scheduledSlots = removeSpacesAndBreaks(scheduledSlots);
  scheduledSlots = parseScheduleString(scheduledSlots);

  let scheduledTime = [];

  scheduledTime.push(
    ...scheduledSlots.filter((item) => item.day === selectedDay)
  );

  scheduledTime = scheduledTime.map((item) => [item.start, item.end]);

  let index = scheduledSlots.findIndex((item) => item.day === selectedDay);

  if (index < 0) {
    index = findLastIndex(
      scheduledSlots,
      (item) => item.day === selectedDay - 1
    );

    index++;
  }

  let scheduledTimeToday = [];
  let scheduledTimePrev = [];

  if (index > 0) {
    if (scheduledSlots[index - 1].day === selectedDay - 1) {
      scheduledTimePrev.push(scheduledSlots[index - 1]);

      scheduledTimePrev = scheduledTimePrev.map((item) => [
        item.start,
        item.end,
      ]);

      const timeStart = new Time(scheduledTimePrev[0][0]);
      const timeEnd = new Time(scheduledTimePrev[0][1]);

      let newTimeArr = [];

      if (timeStart.compare(timeEnd.toString()) > 0) {
        timeStart.setTime(0, 0);
        newTimeArr.push(timeStart.toString());
        newTimeArr.push(timeEnd.toString());
        scheduledTimeToday.push(newTimeArr);
      }
    }
  }

  scheduledTime.forEach((time) => {
    const timeStart = new Time(time[0]);
    const timeEnd = new Time(time[1]);
    let newTimeArr = [];
    if (timeStart.compare(timeEnd.toString()) > 0) {
      timeEnd.setTime(23, 59);
      newTimeArr.push(timeStart.toString());
      newTimeArr.push(timeEnd.toString());
      scheduledTimeToday.push(newTimeArr);
    } else {
      newTimeArr.push(timeStart.toString());
      newTimeArr.push(timeEnd.toString());
      scheduledTimeToday.push(newTimeArr);
    }
  });

  let freeTime;

  if (selectedDate === now.toCustomDate()) {
    const timeArrayToday = createTimeArray(
      Number(now.getHours()) + 1,
      END_HOUR,
      TIME_STEP_MINUTES
    );
    freeTime = getFreeTime(timeArrayToday, scheduledTimeToday);
  } else {
    freeTime = getFreeTime(timeArray, scheduledTimeToday);
  }

  let freeButtons = [];
  let i = 0;

  freeTime.forEach(function (time) {
    let button = document.createElement("button");
    button.textContent = time;
    button.classList.add("feedback__time-button");
    if (!isVisible) {
      button.style.display = "none";
    }

    const regex = /^\d{2}:30$/;

    if (regex.test(time)) {
      button.style.display = "none";
    } else {
      let tempTime = new Time(freeTime[i]);
      tempTime.addTime(0, 30);
      if (tempTime.toString() !== freeTime[i + 1]) {
        return;
      }
    }

    timeContainer.appendChild(button);
    freeButtons.push(button);
    i++;
  });

  freeButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      e.preventDefault();

      let freeTimeStep =
        myBookingForm.freeTime.totalMinutes() / TIME_STEP_MINUTES;

      if (freeTimeStep !== 0) {
        let isRightChoice = true;
        let preTime = new Time(button.textContent);
        for (let i = 1; i <= freeTimeStep; i++) {
          preTime.subtractTime(0, TIME_STEP_MINUTES);
          const preButton = getPreviousElement(button, i);
          if (preButton) {
            if (preButton.textContent !== preTime.toString()) {
              isRightChoice = false;
            }
          } else {
            isRightChoice = false;
          }
        }
        if (!isRightChoice) {
          alert(
            "Выберите другое время, чтобы воспользоваться бесплатными минутами"
          );
          return;
        }
      }

      freeButtons.forEach((button) => {
        button.classList.remove("feedback__time-button--checked");
      });

      counterNum.value = "01:00";
      counterNum.dataset.increment = 2;

      button.classList.add("feedback__time-button--checked");

      let endTime = new Time(button.textContent);
      endTime.addTime(1, 0);

      myBookingForm.setTime("dateTimeStart", button.textContent);

      const tempDate = myBookingForm.getDate("dateTimeStart");
      myBookingForm.setDate("dateTimeEnd", tempDate);

      myBookingForm.setTime("dateTimeEnd", endTime.toString());

      endTimeText.textContent =
        "до " +
        myBookingForm.getTime("dateTimeEnd") +
        " " +
        myBookingForm.dateTimeEnd.toDefaultDate();
    });
  });
}

function increaseCounter() {
  const currentValue = counterNum.value;
  const currentTime = new Time(currentValue);
  currentTime.addTime(1, 0);
  counterNum.value = currentTime.toString();
  counterNum.dataset.increment = Number(counterNum.dataset.increment) + 2;
}

function decreaseCounter() {
  const currentValue = counterNum.value;
  const currentTime = new Time(currentValue);
  currentTime.subtractTime(1, 0);
  counterNum.value = currentTime.toString();
  counterNum.dataset.increment = Number(counterNum.dataset.increment) - 2;
}

counterMinus.addEventListener("click", (e) => {
  e.preventDefault();
  const checkedButton = timeContainer.querySelector(
    ".feedback__time-button--checked"
  );

  if (checkedButton) {
    if (counterNum.value !== "01:00") {
      decreaseCounter();
      myBookingForm.setRentalHours(counterNum.value);
    }

    const counterTime = new Time(counterNum.value);

    let endTime = new Time(checkedButton.textContent);
    endTime.addTime(0, counterTime.totalMinutes());

    if (endTime.hours >= 24) {
      endTime.resetTime();

      let diffTime = new Time(checkedButton.textContent).totalMinutes();

      diffTime = MINUTES_PER_DAY - diffTime;

      const tempCount = Number(counterTime.totalMinutes()) - diffTime;

      endTime.addTime(0, tempCount);
    }

    myBookingForm.setTime("dateTimeEnd", endTime.toString());

    endTimeText.textContent =
      "до " +
      myBookingForm.getTime("dateTimeEnd") +
      " " +
      myBookingForm.dateTimeEnd.toDefaultDate();

    if (endTime.toString() === "00:00") {
      const tempDate = myBookingForm.getDate("dateTimeStart");
      myBookingForm.setDate("dateTimeEnd", tempDate);
    }
  }
});

counterPlus.addEventListener("click", (e) => {
  e.preventDefault();
  const checkedButton = timeContainer.querySelector(
    ".feedback__time-button--checked"
  );

  if (counterNum.value === "24:00") {
    return;
  }

  if (checkedButton) {
    myBookingForm.setTime("dateTimeStart", checkedButton.textContent);

    const nextButton = getNextSiblingByIndex(
      checkedButton,
      Number(counterNum.dataset.increment)
    );

    const counterTime = new Time(counterNum.value);

    let nextTime = new Time(checkedButton.textContent);
    nextTime.addTime(0, counterTime.totalMinutes());

    let endTime = new Time(checkedButton.textContent);
    endTime.addTime(1, counterTime.totalMinutes());

    if (nextTime.hours >= 24) {
      nextTime.resetTime();
      endTime.resetTime();
      let diffTime = new Time(checkedButton.textContent).totalMinutes();

      diffTime = MINUTES_PER_DAY - diffTime;

      const tempCount = Number(counterTime.totalMinutes()) - diffTime;

      nextTime.addTime(0, tempCount);
      endTime.addTime(1, tempCount);
    }

    let compareTime = nextTime.compare(nextButton.textContent);

    if (compareTime === 0) {
      increaseCounter();

      myBookingForm.setTime("dateTimeEnd", endTime.toString());

      endTimeText.textContent =
        "до " +
        myBookingForm.getTime("dateTimeEnd") +
        " " +
        myBookingForm.dateTimeEnd.toDefaultDate();

      myBookingForm.setRentalHours(counterNum.value);
    } else {
      endTime.subtractTime(1, 0);
      myBookingForm.setTime("dateTimeEnd", endTime.toString());

      alert(
        "Вы можете арендовать до " +
          myBookingForm.getTime("dateTimeEnd") +
          " включительно"
      );
      return;
    }
  } else {
    alert("Выберите время начала");
    return;
  }
});

personalData.addEventListener("change", function () {
  myBookingForm.dataPolicyConsent = this.checked;
});

formSend.addEventListener("click", async (e) => {
  e.preventDefault();

  const dataToSend = {
    zal: myBookingForm.zal,
    name: myBookingForm.name,
    phone: myBookingForm.phone,
    mainService: myBookingForm.mainService,
    addService: myBookingForm.addService,
    startTime: myBookingForm.getTime("dateTimeStart"),
    startDate: myBookingForm.getDate("dateTimeStart"),
    endTime: myBookingForm.getTime("dateTimeEnd"),
    endDate: myBookingForm.getDate("dateTimeEnd"),
    freeTime: myBookingForm.freeTime.totalMinutes(),
    rentalHours: myBookingForm.rentalHours,
  };

  if (!myBookingForm.name.trim() || !myBookingForm.phone.trim()) {
    alert("Пожалуйста, заполните обязательные поля: Имя и Телефон.");
    return;
  }

  if (!myBookingForm.dataPolicyConsent) {
    alert(
      "Пожалуйста, отметьте галочку, чтобы подтвердить согласие с политикой обработки персональных данных."
    );
  }

  window.location.replace(REDIRECT_URL);

  const dataJSON = JSON.stringify(dataToSend);

  try {
    const response = await fetch(API_URL, { method: "POST", body: dataJSON });
    if (response.ok) {
      console.log("Данные отправлены успешно");
      bookingForm.reset();
    } else {
      console.log("Произошла ошибка при отправке данных");
    }
  } catch (error) {
    console.error("Произошла ошибка: " + error.message);
  }
});
