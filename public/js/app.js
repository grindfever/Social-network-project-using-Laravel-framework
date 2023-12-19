  
  function addEventListeners() {

    let postDeleter = document.querySelector('button#delete-post');
    if(postDeleter != null)
      postDeleter.addEventListener('click', sendDeletePostRequest);
    
    let postCreator = document.querySelector('form.new_post');
    if (postCreator != null)
      postCreator.addEventListener('submit', sendCreatePostRequest);
    
    let postEditor = document.querySelector('button#edit-post');
    if (postEditor != null){
      postEditor.addEventListener('click', editablePost);
    }
  
    let messageCreator = document.querySelector('article.message form.new_message');
    if (messageCreator != null)
      messageCreator.addEventListener('submit', sendCreateMessageRequest);

    let searchForm = document.querySelector('form.d-flex');
    if (searchForm !== null){  
      searchForm.addEventListener('submit', sendSearchRequest);
    }

  }
  
  /*
  function encodeForAjax(data) {
    if (data == null) return null;
    
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
    \
    request.open(method, url, true)
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));

  }
  */

  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
    let formData = new FormData();

    for(let key in data) {
      formData.append(key, data[key]);
    }
    
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.addEventListener('load', handler);
    request.send(formData);
  }
  
  function editablePost() {
      let contentDiv = document.querySelector('div.content');
      let titleHeader = document.querySelector('h1'); // Adjust the selector if your title is in a different header tag

      // Create editable textareas for content and title
      let contentInput = document.createElement('textarea');
      contentInput.name = 'content';
      contentInput.classList.add('edit-textarea');
      contentInput.textContent = contentDiv.textContent;
      contentDiv.replaceWith(contentInput);

      let titleInput = document.createElement('textarea');
      titleInput.name = 'title';
      titleInput.classList.add('edit-textarea');
      titleInput.textContent = titleHeader.textContent;
      titleHeader.replaceWith(titleInput);

      // Show save button
      let saveButton = document.querySelector('button#save-post');
      saveButton.style.display = 'block';

      // Add event listener to save changes on submit
      saveButton.addEventListener('click', function() {
        saveEditedPost(contentInput.value, titleInput.value);
      });
  }

  function saveEditedPost(content, title) {
    // Perform AJAX request to save the edited post
    let id = document.querySelector('#delete-post').getAttribute('data-post-id');
    sendAjaxRequest('put', '/api/post/' + id, { content: content, title: title }, postEditedHandler);
  }

  function postEditedHandler() {
    if (this.status === 200) {
      // Handle successful post edit
      console.log('Post edited successfully');

      // Hide save button
      let saveButton = document.querySelector('button#save-post');
      saveButton.style.display = 'none';

      // Remove text areas
      let contentInput = document.querySelector('textarea[name=content]');
      let titleInput = document.querySelector('textarea[name=title]');
      let contentDiv = document.createElement('div');
      contentDiv.classList.add('content');
      let titleHeader = document.createElement('h1');

      contentDiv.textContent = contentInput.value;
      titleHeader.textContent = titleInput.value;

      contentInput.replaceWith(contentDiv);
      titleInput.replaceWith(titleHeader);
    } else {
      // Handle error in post edit
      console.log('Failed to edit the post. Please try again.');
    }
  }

  function sendDeletePostRequest() {
    let deleteButton = document.querySelector('#delete-post');
    let id = deleteButton.getAttribute('data-post-id');
    sendAjaxRequest('delete', '/api/post/' + id, null, postDeletedHandler);
  }

  function sendCreatePostRequest(event) {
    event.preventDefault();

    let content_post = this.querySelector('form.new_post textarea[name=content]').value;
    let title_post = this.querySelector('form.new_post input[name=title]').value;
    let type = this.querySelector('form.new_post input[name=type]').value;
    let fileInput = this.querySelector('form.new_post input[name=file]');
    let file = fileInput.files[0];

    if (title_post != '' && content_post != '') {
      let data = {
        content: content_post,
        title: title_post,
        type: type
      };

      if (file) {
        data.file = file;
      }
      console.log(data);
      sendAjaxRequest('post', '/dashboard/create', data, postAddedHandler);
    }
  }
  
  function postDeletedHandler() {
    if (this.status === 200) window.location = '/dashboard';
    else {
      const errorMessage = document.createElement('div');
      errorMessage.textContent = 'Failed to delete the post. Please try again.';
      errorMessage.style.color = 'red'; 
      document.body.appendChild(errorMessage);
    }
    let post = JSON.parse(this.responseText);
    
    let div = document.getElementById(post.id);
    div.remove();
  }
  
  function postAddedHandler() {
    if (this.status != 200) window.location = '/';
    console.log(this.responseText);
    let post = JSON.parse(this.responseText);

    let new_post = createPost(post);

    let form = document.querySelector('form.new_post');
    form.querySelector('[name=content]').value="";
    form.querySelector('[name=title]').value="";
    form.querySelector('[name=file]').value="";

    let section = document.querySelector('section.dashboard');
    section.prepend(new_post);
    
    content.scrollTop = content.scrollHeight;
  }
  
  function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
  }

  function createPost(post) {
    let new_post = document.createElement('article');
    let imgHTML = '';
    let currentURLhost = window.location.host;
    let currentURLprotocol = window.location.protocol;
    let currentURL = currentURLprotocol + '//' + currentURLhost;
   
    
    if (typeof post.file === 'string' && post.file.length > 0) {
      imgHTML = `<img class="post-image" src="${currentURL}/post/${post.file}">`;
    }

    if (typeof post.post.user.img === 'string' && post.post.user.img.length > 0) {
      avatarHTML = `<img src="${currentURL}/profile/${post.post.user.img}" class="avatar">`;
    }
    else{
      avatarHTML = `<img src="${currentURL}/profile/default.jpg" class="avatar">`;
    }

    // Get the extension of the file
    let extension = post.file.split('.').pop();

    // Create a new element based on the file type
    let mediaElement;
    if (['mp4', 'webm', 'ogg'].includes(extension)) {
      mediaElement = document.createElement('video');
      mediaElement.setAttribute('controls', '');
      let source = document.createElement('source');
      source.src = post.file;
      source.type = `video/${extension}`;
      mediaElement.appendChild(source);
    } else if (['mp3', 'wav', 'ogg'].includes(extension)) {
      mediaElement = document.createElement('audio');
      mediaElement.setAttribute('controls', '');
      let source = document.createElement('source');
      source.src = post.file;
      source.type = `audio/${extension}`;
      mediaElement.appendChild(source);
    } else {
      mediaElement = document.createElement('img');
      mediaElement.src = post.file;
      mediaElement.className = 'post-image';
    }

    new_post.classList.add('post');
    new_post.setAttribute('data-id', post.post.id);
    new_post.innerHTML = `
      <div class="card-header">
        <a href="/profile/${post.post.user.id}">
          ${avatarHTML}
          ${post.post.user.name}
        </a>
        <span class="float-end">Just now</span>
      </div>
      <h1>${post.post.title}</h1>
      <div class="card-body">
        <p class="card-text">
          <div class="content">${post.post.content}</div>
          ${mediaElement}
        </p>
      </div>
      <div class="like-post">
        <button type="submit" class="like-count">
          <span class="far fa-heart"></span> 0
        </button>
      </div>
    `;


    // Create a div element, add a class to it, and append new_post to it
    let wrapper = document.createElement('div');
    wrapper.classList.add('post-border');
    wrapper.id = post.post.id;
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
      sendAjaxRequest('delete', '/api/post/'+id+'/unlike', null, unlikeHandler);
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
    let id =  this.closest('div').getAttribute('data-id');
    
    let textareaContent = document.querySelector('div.comments[data-id="' + id + '"] form.new_comment #exampleTextarea').value;

    if (textareaContent != ''){      
      sendAjaxRequest('post', '/api/post/' + id + '/comment', {content: textareaContent}, commentAddedHandler);
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
   
    let textareaContent = document.querySelector('div.form-group textarea#exampleTextarea');
    textareaContent.value = "";

    let ul = document.querySelector('ul.list-group.list-group-flush');
    ul.prepend(new_comment);
    
  }

  function createComment(comment) {
    let new_comment = document.createElement('div');
    new_comment.classList.add('comment-container');
    new_comment.setAttribute('data-id', comment.comment.id);

    let currentURLhost = window.location.host;
    let currentURLprotocol = window.location.protocol;
    let currentURL = currentURLprotocol + '//' + currentURLhost;
   
    if(comment.user.img != null){
      avatarHTML = `<img src="${currentURL}/profile/${comment.user.img}" class="avatar">`;
    }
    else{
      avatarHTML = `<img src="${currentURL}/profile/default.jpg" class="avatar">`;
    }

    new_comment.innerHTML = `
      <a href="/profile/${comment.user.id}" class="profile_avatar">
        ${avatarHTML}
      </a>
      <span class="float-end">Just now</span>
      <li class="list-group-item">${comment.comment.content}</li>
        <div class="float-end" style="padding-top: 10px;">
          <button class="btn btn-sm btn-primary">Edit</button>
          <button class="btn btn-sm btn-danger">Delete</button>
        </div>
    `;

    return new_comment;
  }
 
  // ########## EDIT AND DELETE COMMENTS  ##############


  function sendDeleteCommentRequest(commentID) {
    
    sendAjaxRequest('delete', '/api/comment/' + commentID, null, commentDeletedHandler);
  }

  function commentDeletedHandler() {
    if (this.status === 200) {
      let comment = JSON.parse(this.responseText);
      deleteComment(comment.id);
      console.log('Comment deleted successfully');
    }
    else {
      console.log('Failed to delete the comment. Please try again.');
    }
  }

  function editComment(commentId) {
    // Find the comment element with the given commentId
    let commentElement = document.querySelector(`div.comment-container[data-id="${commentId}"]`);
  
    // Get the comment content
    let commentContent = commentElement.querySelector('li.list-group-item').textContent;

    // Create an input element for editing the comment
    let inputElement = document.createElement('input');
    inputElement.type = 'text';
    inputElement.value = commentContent;

    // Replace the comment content with the input element
    commentElement.querySelector('li.list-group-item').textContent = '';
    commentElement.querySelector('li.list-group-item').appendChild(inputElement);

    // Create a save button for saving the edited comment
    let saveButton = document.createElement('button');
    saveButton.textContent = 'Save';
    saveButton.classList.add('btn', 'btn-sm', 'btn-primary');

 
    saveButton.addEventListener('click', function() {
      sendEditCommentRequest(commentId, inputElement.value);
    });

    // Replace the edit button with the save button
    let editButton = commentElement.querySelector('button.btn-primary');
    editButton.replaceWith(saveButton);
  }

  function sendEditCommentRequest(commentID,content) {
 
    sendAjaxRequest('put', '/api/comment/' + commentID, {content: content}, commentEditedHandler);
  }

  function commentEditedHandler() {

    let comment = JSON.parse(this.responseText);


    // Find the comment element with the given commentId
    let commentElement = document.querySelector(`div.comment-container[data-id="${comment.comment.id}"]`);

    // Get the edited comment content
    let editedCommentContent = commentElement.querySelector('li.list-group-item input').value;


    // Update the comment content
    commentElement.querySelector('li.list-group-item').textContent = editedCommentContent;

    // Create an edit button for editing the comment again
    let editButton = document.createElement('button');
    editButton.textContent = 'Edit';
    editButton.classList.add('btn', 'btn-sm', 'btn-primary');
    editButton.addEventListener('click', function() {
      editComment(commentId);
    });

    // Replace the save button with the edit button
    let saveButton = commentElement.querySelector('button.btn-primary');
    saveButton.replaceWith(editButton);
  }

  function deleteComment(commentId) {
    // Find the comment element with the given commentId
    let commentElement = document.querySelector(`div.comment-container[data-id="${commentId}"]`);

    // Remove the comment element from the DOM
    commentElement.remove();
  }

  // ########## SEARCH  ##############

  // Add event listener for the document click
  document.addEventListener('click', function(event) {
    const postId = event.target.closest('section.dashboard article.post'); // Find the closest ancestor with class 'post'

    if (postId) {
      let postIdValue = postId.getAttribute('data-id'); // Get the post id value
      // Check if the clicked element was not an interactive element within the post
      const interactiveElements = ['a', 'button', 'input', 'textarea','span'];
      const isInteractive = interactiveElements.includes(event.target.tagName.toLowerCase());

      // If the clicked element is not interactive, navigate to the post page
      if (!isInteractive) {
        window.location.href = `/post/${postIdValue}`;
      }
    }
  });

  function sendSearchRequest(event){
    let name = event.target.querySelector('input[name="query"]').value.trim();
    if (name != '')
      sendAjaxRequest('post', '/dashboard/search', {query: name}, searchHandler);

    event.preventDefault();
  }

  function searchHandler() {
    console.log('Search handler called.');
    
    if (this.status != 200) {
        console.log('Error: ', this.status);
        window.location = '/';
        return;
    }
    let searchResults = JSON.parse(this.responseText);

    let searchResultContainer = document.querySelector('.search-results .list-group');
    if (!searchResultContainer) {
        searchResultContainer = document.createElement('div');
        searchResultContainer.classList.add('list-group');
        document.querySelector('.search-results').appendChild(searchResultContainer);
    } 
    else {
      searchResultContainer.innerHTML = '';
    }

    var gotResults = false;

    if (searchResults.users.length > 0) {
        gotResults = true;
        let userHeading = document.createElement('h2');
        userHeading.textContent = 'Users';
        searchResultContainer.appendChild(userHeading);

        searchResults.users.forEach(user => {
            let link = document.createElement('a');
            link.href = '/profile/' + user.id;
            link.textContent = user.name;

            link.classList.add('list-group-item', 'list-group-item-action');

            searchResultContainer.appendChild(link);
        });
    } 
    if (searchResults.posts.length > 0) {
        gotResults = true;
        let postsHeader = document.createElement('h2');
        postsHeader.textContent = 'Posts';
        searchResultContainer.appendChild(postsHeader);

        searchResults.posts.forEach(post => {
            let link = document.createElement('a');
            link.href = '/post/' + post.id;
            link.textContent = post.title;

            link.classList.add('list-group-item', 'list-group-item-action');

            searchResultContainer.appendChild(link);
        });
    }

    if (!gotResults){
      let noResult = document.createElement('p');
      searchResultContainer.appendChild(noResult);
    }

    let clearButton = document.createElement('button');
    clearButton.innerHTML = 'Clear Results';
    clearButton.onclick = clearSearchResults;
    searchResultContainer.appendChild(clearButton);


    showSearchResults();
}

function showSearchResults(){
  let searchResultContainer = document.querySelector('.search-results-container');
  searchResultContainer.style.display = "block";
}
 
 function clearSearchResults(){
  let searchResult = document.querySelector('.search-results');
  searchResult.innerHTML = '';
  let searchResultContainer = document.querySelector('.search-results-container');
  searchResultContainer.style.display = "none";
}
  

  addEventListeners();
  

  
  