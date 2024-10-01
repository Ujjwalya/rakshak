let availabilities = [];

function addAvailability() {
    const availabilityInput = document.getElementById('availability');
    const availability = availabilityInput.value.trim();
    
    if (availability !== '') {
        availabilities.push(availability);
        updateAvailabilityList();
        availabilityInput.value = '';
        findCommonSlots();
    } else {
        alert('Please enter your availability.');
    }
}

function updateAvailabilityList() {
    const availabilityList = document.getElementById('availability-list');
    availabilityList.innerHTML = '';
    availabilities.forEach(availability => {
        const li = document.createElement('li');
        li.textContent = availability;
        availabilityList.appendChild(li);
    });
}

function findCommonSlots() {
    const availabilityArrays = availabilities.map(availability => {
        const [day, timeRange] = availability.split(' ');
        const [startTime, endTime] = timeRange.split(' - ');
        return { day, startTime: timeStringToNumber(startTime), endTime: timeStringToNumber(endTime) };
    });

    const commonSlots = [];
    for (let hour = 0; hour <= 23; hour++) {
        const timeSlotStart = hour * 100;
        const timeSlotEnd = (hour + 1) * 100;
        const available = availabilityArrays.every(availability => {
            return availability.startTime <= timeSlotStart && availability.endTime >= timeSlotEnd;
        });
        if (available) {
            const startTime = timeNumberToString(timeSlotStart);
            const endTime = timeNumberToString(timeSlotEnd);
            commonSlots.push(`${startTime} - ${endTime}`);
        }
    }

    const commonSlotsElement = document.getElementById('common-slots');
    if (commonSlots.length > 0) {
        commonSlotsElement.textContent = commonSlots.join(', ');
    } else {
        commonSlotsElement.textContent = 'No common available slots.';
    }
}

function timeStringToNumber(timeString) {
    const [hours, minutes] = timeString.split(':').map(Number);
    return hours * 100 + minutes;
}

function timeNumberToString(timeNumber) {
    const hours = Math.floor(timeNumber / 100);
    const minutes = timeNumber % 100;
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
}
