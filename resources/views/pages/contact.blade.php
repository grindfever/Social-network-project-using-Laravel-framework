@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<section id="contact-info">
    <h1>Contact Us</h1>
    <p>Feel free to reach out to us for any inquiries or feedback. We're here to help!</p>
</section>

<section id="contact-form">
    <h2>Contact Form</h2>
    <form action="/contact" method="post">
        @csrf
        <div class="form-group">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="message">Your Message:</label>
            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</section>

<section id="contact-details">
    <h2>Contact Details</h2>
    <p>If you prefer to contact us through other means, here are our contact details:</p>
    <ul>
        <li>Email: info@lbaw23102.com</li>
        <li>Phone: +351 934644049</li>
        <li>Address: Rua Dr. Roberto Frias, s/n 4200-465 Porto Portugal</li>
    </ul>
</section>
@endsection
