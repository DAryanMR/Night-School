function convertTimeToMinutes(time) {
    var parts = time.split(":");
    var hours = parseInt(parts[0]);
    var minutes = parseInt(parts[1]);
    return hours * 60 + minutes;
}
