@extends('layouts.app')

@section('content')
    <section class="row new-post">
        <header><h3>What do you have to say?</h3></header>
        <form action="">
            <div class="form group">
                <textarea class="form-control" name="body" id="new-post" rows="5" placeholder="Your post"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </section>
    <section class="row posts">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>TEXT POST</h3></header>
            <article class='post'>
                <p>Suspendisse vitae vestibulum libero. Aenean et convallis neque, id faucibus magna. Pellentesque nec venenatis elit, vitae tempor quam. Praesent ac convallis nibh. Suspendisse potenti. Proin sed mi id dolor eleifend ultricies. Aliquam at metus vitae tortor ultricies elementum. Vestibulum consectetur felis sed gravida rutrum. 
            Morbi rhoncus fringilla augue at vestibulum. </p>
            <div class="info">
                Posted by tester on 12 nov 2023
            </div>
            <div class="interaction">
                <a href="#">Like</a> |
                <a href="#">Dislike</a> |
                <a href="#">Edit</a> |
                <a href="#">Delete</a>
            </div>
            </article>
            <article class='post'>
                <p>Suspendisse vitae vestibulum libero. Aenean et convallis neque, id faucibus magna. Pellentesque nec venenatis elit, vitae tempor quam. Praesent ac convallis nibh. Suspendisse potenti. Proin sed mi id dolor eleifend ultricies. Aliquam at metus vitae tortor ultricies elementum. Vestibulum consectetur felis sed gravida rutrum. 
            Morbi rhoncus fringilla augue at vestibulum. </p>
            <div class="info">
                Posted by tester on 12 nov 2023
            </div>
            <div class="interaction">
                <a href="#">Like</a> |
                <a href="#">Dislike</a> |
                <a href="#">Edit</a> |
                <a href="#">Delete</a>
            </div>
            </article>
        </div>

    </section>
@endsection