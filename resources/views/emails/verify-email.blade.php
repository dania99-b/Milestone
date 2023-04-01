<x-mail::message>
    <div style="text-align:center;">
        <img src="{{ url('https://i.postimg.cc/nrxc1tKz/Milestone-Logo.jpg') }}" alt="Milestone logo" style="width: 500px;">
    </div>
    <h1 style="color: #2D527E; text-align: center; margin-top: 20px;">Welcome to Milestone, {{ $user->email}} !</h1>
    <p style="color: #2D527E;">Thank you for signing up. To verify your account, please use the code below:</p>
    <h2 style="color: #2D527E; font-size: 24px; text-align: center;">{{ $user->verification_code }}</h2>
    <p style="color: #2D527E;">If you did not sign up for Milestone, please disregard this email.</p>
    <p style="color: #2D527E;">We are excited to have you on board and look forward to serving you.</p>
    <p style="color: #2D527E;">If you have any questions or concerns, please don't hesitate to reach out to our support team at support@example.com.</p>
    <p style="color: #2D527E;">Thank you for choosing Milestone!</p>
</x-mail::message>
