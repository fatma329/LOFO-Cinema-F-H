<?php
session_start();
include('php/db.php');  // Include the database connection file

if (isset($_POST['selectedSeats'])) {
    $selectedSeats = json_decode($_POST['selectedSeats']);
    $user_id = $_SESSION['user_id']; // Assuming the user is logged in

    if ($user_id && !empty($selectedSeats)) {
        $errors = [];
        $total_price = 228;
        $seat_numbers = [];

        foreach ($selectedSeats as $ticket_id) {
            // Check if the seat is still available
            $seat_check_sql = "
                SELECT seats.is_available, seats.seat_number, tickets.price
                FROM tickets
                JOIN seats ON tickets.seat_id = seats.id
                WHERE tickets.id = $ticket_id
            ";
            $seat_check_result = $conn->query($seat_check_sql);
            if ($seat_check_result->num_rows > 0) {
                $seat_check = $seat_check_result->fetch_assoc();
                if ($seat_check['is_available'] == 1) {
                    // Insert booking into the database
                    $booking_sql = "INSERT INTO bookings (user_id, ticket_id, booking_time) VALUES ($user_id, $ticket_id, NOW())";
                    if ($conn->query($booking_sql) === TRUE) {
                        // Update seat availability
                        $update_seat_sql = "
                            UPDATE seats
                            JOIN tickets ON seats.id = tickets.seat_id
                            SET seats.is_available = 0
                            WHERE tickets.id = $ticket_id
                        ";
                        $conn->query($update_seat_sql);

                        // Collect booking details
                        $total_price += $seat_check['price'];
                        $seat_numbers[] = $seat_check['seat_number'];
                    } else {
                        $errors[] = "Error: " . $booking_sql . "<br>" . $conn->error;
                    }
                } else {
                    $errors[] = "Seat already booked.";
                }
            } else {
                $errors[] = "Seat not found.";
            }
        }
        
        if (empty($errors)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Booking confirmed!',
                'seat_numbers' => $seat_numbers,
                'total_price' => $total_price
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => implode(", ", $errors)
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid user ID or no seats selected.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No seats selected.'
    ]);
}
?>
