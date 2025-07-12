<?php
include('php/db.php');  // Include the database connection file

if (isset($_GET['showtime_id'])) {
    $showtime_id = intval($_GET['showtime_id']);
    $seats_sql = "
        SELECT tickets.id AS ticket_id, seats.seat_number, seats.is_available
        FROM tickets
        JOIN seats ON tickets.seat_id = seats.id
        WHERE tickets.showtime_id = $showtime_id
    ";
    $seats_result = $conn->query($seats_sql);

    if ($seats_result->num_rows > 0) {
        while ($seat = $seats_result->fetch_assoc()) {
            $seat_class = $seat['is_available'] ? 'available' : 'booked';
            echo "<button class='seat $seat_class' data-ticket-id='" . $seat['ticket_id'] . "'>" . $seat['seat_number'] . "</button>";
        }
    } else {
        echo "<p>No seats available for this showtime.</p>";
    }
} else {
    echo "<p>No showtime ID provided.</p>";
}
?>
