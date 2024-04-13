document.addEventListener("DOMContentLoaded", function () {
  const datePicker = document.getElementById("appointment-date");
  const timePicker = document.getElementById("appointment-time");

  datePicker.addEventListener("change", function () {
    const selectedDay = new Date(this.value).getDay();

    // Check if the selected day is Sunday (0), Monday (1), or Friday (5)
    if ([0, 1].includes(selectedDay)) {
      alert("Op zondag, maandag zijn er geen afspraken mogelijk");
      this.value = "";
      timePicker.innerHTML = "";
    } else {
      populateTimeSlots(selectedDay);
    }
  });

  function populateTimeSlots(selectedDay) {
    let startTime, endTime;

    switch (selectedDay) {
      case 0: // Sunday
      case 1: // Monday
        startTime = 10 * 60; // 10:00 AM in minutes
        endTime = 17 * 60 + 45; // 17:45 PM in minutes
        break;
      case 4: // Thursday
        startTime = 10 * 60; // 10:00 AM in minutes
        endTime = 19 * 60 + 45; // 6:45 PM in minutes
        break;
      case 2: // Tuesday
      case 3: // Wednesday
        startTime = 10 * 60; // 10:00 AM in minutes
        endTime = 17 * 60 + 45; // 17:45 PM in minutes
        break;
      case 5: // Friday
        startTime = 10 * 60; // 10:00 AM in minutes
        endTime = 17 * 60 + 30; // 5:30 PM in minutes
        break;
      case 6: // Saturday
        startTime = 9 * 60 + 30; // 9:30 AM in minutes
        endTime = 17 * 60; // 5:00 PM in minutes
        break;
      default:
        startTime = 10 * 60;
        endTime = 17 * 60 + 45;
        break;
    }

    let options = '<option value="">Select a Time</option>';

    for (let minutes = startTime; minutes < endTime; minutes += 15) {
      const hour = Math.floor(minutes / 60);
      const minute = minutes % 60;
      let timeString =
        hour.toString().padStart(2, "0") +
        ":" +
        minute.toString().padStart(2, "0");
      options += `<option value="${timeString}">${timeString}</option>`;
    }

    timePicker.innerHTML = options;
  }
});
