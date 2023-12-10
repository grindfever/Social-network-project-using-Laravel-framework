function addEventListeners() {
    
  let postDeleters = document.querySelectorAll('article.post button#delete-post');
  [].forEach.call(postDeleters, function(deleter) {
    deleter.addEventListener('click', sendDeletePostRequest);
  });

  let postCreator = document.querySelector('article.post form.new_post');
  if (postCreator != null)
    postCreator.addEventListener('submit', sendCreatePostRequest);

  let postEditor = document.querySelector('button.edit-post');
  if (postEditor != null){
    postEditor.addEventListener('click', editablePost);
  }

  let messageCreator = document.querySelector('article.message form.new_message');
  if (messageCreator != null)
    messageCreator.addEventListener('submit', sendCreateMessageRequest);

}

  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }
  
  function editablePost(event) {
    let content = document.querySelector('div.content');

    if (!content.isContentEditable) {
      content.setAttribute('contenteditable', 'true');
      content.focus();
      content.style.borderColor = 'red';
      // Create a new button element
      let saveButton = document.createElement('button');
      // Set button properties (e.g., text content, attributes, event listeners)
      saveButton.textContent = 'Save'; // Set button text
      saveButton.setAttribute('class', 'save-button'); // Set button class
      saveButton.addEventListener('click', function () {
        // Add functionality for when the button is clicked
        console.log('post saved');
        let updatedContent = content.textContent.trim();

        // Send AJAX request to update post
        sendUpdatePostRequest(updatedContent, content);
      });
      // Find the reference button (the button above which you want to insert the new button)
      let referenceButton = document.querySelector('.edit-post'); // Replace '.reference-button' with your reference button selector

      // Insert the new button below the reference button using insertAdjacentElement
      referenceButton.insertAdjacentElement('afterend', saveButton);
    }
    event.preventDefault();
  }

  function sendUpdatePostRequest(updatedContent, content) {
    let id = content.closest('article.post').getAttribute('data-id');
    let data = { content: updatedContent };

    sendAjaxRequest('put', '/api/post/' + id, data, function() {
        // Handler for the response after the content is updated
        if (this.status === 200) {
            // Content successfully updated, update the UI
            content.contentEditable = false; // Set content back to non-editable

            // Remove the 'Save' button
            let saveButton = content.nextElementSibling;
            if (saveButton && saveButton.classList.contains('save-button')) {
                saveButton.remove();
            }
        } else {
            // Handle error, for example:
            console.error('Failed to update content.');
        }
    });
  }

  function sendDeletePostRequest() {
    let id = this.closest('article').getAttribute('data-id');

    sendAjaxRequest('delete', '/api/post/' + id, null, postDeletedHandler);
  }

  function sendCreatePostRequest(event) {
    let name = this.querySelector('input[name=content]').value;

    if (name != '')
      sendAjaxRequest('post', '/dashboard', {content: name}, postAddedHandler);

    event.preventDefault();
  }
  
  function postDeletedHandler() {
    if (this.status != 200) window.location = '/';
    let post = JSON.parse(this.responseText);
    console.log(post.id);
    
    let div = document.getElementById(post.id);
    div.remove();
  }
  
  function postAddedHandler() {
    if (this.status != 200) window.location = '/';
    let post = JSON.parse(this.responseText);
   
    let new_post = createPost(post);

    let form = document.querySelector('article.post form.new_post');
    form.querySelector('[type=text]').value="";

    let article = form.parentElement;
    let section = article.parentElement;
    section.insertBefore(new_post, article);


    let content = document.getElementById('content');
    content.scrollTop = content.scrollHeight;
  }
  
  function createPost(post) {
      let new_post = document.createElement('article');
      console.log(post);
      new_post.classList.add('post');
      new_post.setAttribute('data-id', post.id);
      new_post.innerHTML = `
      <div class="card-header"><a href="post/${post.id}">  ${post.user.name} </a></div>
      <div class="card-body">
        <p class="card-text">
          <div class="content">${post.content}</div>
        </p>
      </div>
      <div class="like-post">
      <button type="submit" class="like-count">
        <span class="far fa-heart"></span> 0
      </button>
      <button class="delete-post" data-post-id="${post.id}" type="submit">Delete</button>

  
      <form action="api/post/${post.id}/comment" method="POST">
        <div class="mb-3">
          <textarea class="fs-6 form-control" name="content" rows="1" placeholder="Whats on your mind?"></textarea>
        </div>
      <button type="submit" class="btn btn-primary btn-sm"> Post Comment </button>
      </form>
      `;

      let deleter = new_post.querySelector('button.delete-post');
      deleter.addEventListener('click', sendDeletePostRequest);
      
      // Create a div element, add a class to it, and append new_post to it
      let wrapper = document.createElement('div');
      wrapper.classList.add('card', 'border-dark', 'mb-3');
      wrapper.id = post.id;
      wrapper.style.maxWidth = '20rem'; // Replace 'your-class-name' with the actual class name
      wrapper.appendChild(new_post);

      return wrapper;

  }
  
  function sendCreateMessageRequest(event){
    let id = this.closest('article').getAttribute('data-id');
    let name = this.querySelector('input[name=content]').value;
    if (name != '')
      sendAjaxRequest('post', '/messages/'+id, {content: name}, messageAddedHandler);

    event.preventDefault();
  }

  function messageAddedHandler() {
    if (this.status != 200) window.location = '/';
    let message = JSON.parse(this.responseText);
    let new_message = createMessage(message);

    let messagesList = document.querySelector('ul.messages');
    let li = document.createElement('li');
    li.textContent = message.content;

    // Insert the new message after the last <li> element in the messages list
    messagesList.appendChild(li);

    let form = document.querySelector('article.message form.new_message');
    form.querySelector('[type=text]').value = "";

  }

  function createMessage(message) {
    let new_message = document.createElement('article');
    new_message.classList.add('message');

    new_message.innerHTML = `
      <li>${message.content}</li>
    `;
    return new_message;
  }

  // ########## LIKE BUTTON ##############
  
  //event listener for like button
  let likeButtons = document.querySelectorAll('button.like-count');
  [].forEach.call(likeButtons, function(likeButton) {
    likeButton.addEventListener('click', sendLikeRequest);
  });

  function sendLikeRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
    let likeButton = this;
    if (likeButton.classList.contains('liked')) {
      sendAjaxRequest('delete', 'api/post/'+id+'/unlike', null, unlikeHandler);
    } else {
      sendAjaxRequest('post', '/api/post/'+id+'/like', null, likeHandler);
    }
    event.preventDefault();
  }

  function likeHandler() {
    
    if (this.status == 200) {
      let response = JSON.parse(this.responseText);
      
      let likeButton = document.querySelector('button.like-count[data-post-id="'+response.postId+'"]');
      
      if (response.isLiked) {
        
        likeButton.classList.add('liked');
        likeButton.classList.add('animate');
        likeButton.innerHTML = '<span class="fas fa-heart"></span> ' + response.likeCount;
      }
    }
    else {
      console.error('An error occurred: ' + this.status);
      console.error('Response: ' + this.responseText);
    }
  }
  function unlikeHandler() {
 
    if (this.status == 200) {
      let response = JSON.parse(this.responseText);
   
      let unlikeButton = document.querySelector('button.like-count[data-post-id="'+response.postId+'"]');
      
      if (!response.isLiked) {
        
        unlikeButton.classList.remove('liked');
        unlikeButton.classList.add('animate');
        unlikeButton.innerHTML = '<span class="far fa-heart"></span> ' + response.likeCount;
      }
    }
    else {
      console.error('An error occurred: ' + this.status);
      console.error('Response: ' + this.responseText);
    }
  }

  
  // ########## SCROLL TO TOP ##############
  
  function scrollToTop() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  }

  window.addEventListener('scroll', function() {
    var button = document.getElementById('scrollToTopButton');
    if (window.scrollY > 100) { // Show the button after 100px of scrolling
      button.style.display = "block";
    } else {
      button.style.display = "none";
    }
  });

  // ########## COMMENTS  ##############

  document.addEventListener('DOMContentLoaded', function() {
    let commentCreators = document.querySelectorAll('div.comments form.new_comment');

    commentCreators.forEach(function(commentCreator) {
      let submitButton = commentCreator.querySelector('button[type="submit"]');
      submitButton.addEventListener('click', sendCreateCommentRequest);
    });
  });

  function sendCreateCommentRequest(event){
    let id =  this.closest('article').getAttribute('data-id');
    
    let textareaContent = document.querySelector('div.comments[data-id="' + id + '"] form.new_comment #exampleTextarea').value;
    
    if (textareaContent != ''){
      
      sendAjaxRequest('post', 'api/post/' + id + '/comment', {content: textareaContent}, commentAddedHandler);
    }
    else {
      console.error('Error:', this.status, this.statusText);
    }
    event.preventDefault();
  }

  function commentAddedHandler(){
    if (this.status != 200) window.location = '/'
    let comment = JSON.parse(this.responseText);

    let new_comment = createComment(comment);

    let form = document.querySelector('div.comments[data-id="' + comment.comment.post_id + '"] form.new_comment');
    
    form.querySelector('div.comments[data-id="' + comment.comment.post_id + '"] form.new_comment #exampleTextarea').value = "";

    let formParent = form.parentElement;
  
    formParent.insertBefore(new_comment, form);
  
  }



  function createComment(comment) {
    
    let new_comment = document.createElement('p');
    new_comment.classList.add('fw-light', 'fs-6');
    
    new_comment.innerHTML = `${comment.user.name}: ${comment.comment.content}`;
    return new_comment;
  }
  addEventListeners();
  