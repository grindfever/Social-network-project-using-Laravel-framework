function addEventListeners() {
  let postDeleter = document.querySelector('button#delete-post');
    if(postDeleter != null)
      postDeleter.addEventListener('click', sendDeletePostRequest);

      let deleteButtons = document.querySelectorAll('button#remove-post');
        deleteButtons.forEach(function(button) {
          button.addEventListener('click', sendDeleteAdminPostRequest);
      });
      
      let banButtons = document.querySelectorAll('button#ban');
        banButtons.forEach(function(button) {
          button.addEventListener('click', sendBanRequest);
      });

      let unbanButtons = document.querySelectorAll('button#unban');
        unbanButtons.forEach(function(button) {
          button.addEventListener('click', sendUnbanRequest);
      });

    let friendDeleters = document.querySelectorAll('article.friend button#delete-friend');
    [].forEach.call(friendDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeleteFriendRequest);
    });
  
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

    let groupmessageCreator = document.querySelector('article.message form.new_group_message');
    if (groupmessageCreator != null)
      groupmessageCreator.addEventListener('submit', sendCreateGroupMessageRequest);

    let friendRequestForm = document.getElementById('friendRequestForm');
    if (friendRequestForm) {
        friendRequestForm.addEventListener('submit', sendFriendRequest);
    }

    let friendAccepters = document.querySelectorAll('article.friend button#accept-friend');
    [].forEach.call(friendAccepters, function (accepter) {
        accepter.addEventListener('click', sendAcceptFriendRequest);
    });

    let friendRejecters = document.querySelectorAll('article.friend button#reject-friend');
    [].forEach.call(friendRejecters, function (rejecter) {
        rejecter.addEventListener('click', sendRejectFriendRequest);
    });

    let createModerator = document.querySelector('article.set_moderator button.add_moderator');
    if (createModerator != null)
      createModerator.addEventListener('click',sendCreateModeratorRequest);

    let removeModerator = document.querySelector('article.set_moderator button.remove_moderator');
    if (removeModerator != null)
      removeModerator.addEventListener('click',sendDeleteModeratorRequest);

    }

    function sendAcceptFriendRequest(sender, receiver) {
      fetch('/friendrequests/accept/' + sender + '/' + receiver, {
          method: 'POST',
          headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Content-Type': 'application/json',
          },
          body: JSON.stringify({}),
      })
      .then(response => response.json())
      .then(acceptFriendRequestHandler)
      .catch(error => console.error('Error:', error));
  }
  
  function sendFriendRequest(event) {
    event.preventDefault();

    let sender = event.target.getAttribute('data-sender');
    let receiver = event.target.getAttribute('data-receiver');

    fetch(`/profile/${receiver}/send-friend-request`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({}),
    })
    .then(response => response.json())
    .then(sendFriendRequestHandler)
    .catch(error => console.error('Error:', error));
}

function sendFriendRequestHandler(response) {
  if (response.success) {
      console.log('Friend request sent successfully');

      let sender = response.sender;
      let receiver = response.receiver;
      
      console.log('Attempting to remove friend request item:', sender, receiver);

      let friendRequestItem = document.querySelector(`div[data-sender="${sender}"][data-receiver="${receiver}"]`);
      
      if (friendRequestItem) {
          friendRequestItem.remove();
          console.log('Friend request item removed from the DOM');
      } else {
          console.log('Friend request item not found in the DOM');
      }
  } else {
      console.error('Error:', response.message);
  }
}

  function sendRejectFriendRequest(sender, receiver) {
      fetch('/friendrequests/reject/' + sender + '/' + receiver, {
          method: 'POST',
          headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Content-Type': 'application/json',
          },
          body: JSON.stringify({}),
      })
      .then(response => response.json())
      .then(rejectFriendRequestHandler)
      .catch(error => console.error('Error:', error));
  }
  
  function acceptFriendRequestHandler(response) {
      if (response.message === 'Friend request accepted successfully') {
    
          console.log('Friend request accepted');

          let requestItem = document.querySelector(`div[data-sender="${response.sender}"][data-receiver="${response.receiver}"]`);
          if (requestItem) {
              requestItem.remove();
          }
      } else {
          console.error('Error:', response.message);
      }
  }
  
  function rejectFriendRequestHandler(response) {
      if (response.message === 'Friend request rejected successfully') {
          console.log('Friend request rejected');
       
          let requestItem = document.querySelector(`div[data-sender="${response.sender}"][data-receiver="${response.receiver}"]`);
          if (requestItem) {
              requestItem.remove();
          }
      } else {
          console.error('Error:', response.message);
      }
  }
  

  function encodeForAjax(data) {
    if (data == null) return null;
    
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
    
    request.open(method, url, true)
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));

  }
  
  function sendAjaxRequestFormData(method, url, data, handler) {
    let request = new XMLHttpRequest();
    let formData = new FormData();

    for(let key in data) {
      formData.append(key, data[key]);
    }
    for (let pair of formData.entries()) {
      console.log(pair[0]+ ', ' + pair[1]); 
    }
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.addEventListener('load', handler);
    request.send(formData);
  }
  
  function editablePost() {
      let contentDiv = document.querySelector('div.content');
      let titleHeader = document.querySelector('h1'); 

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

      
      let saveButton = document.querySelector('button#save-post');
      saveButton.style.display = 'block';

      
      saveButton.addEventListener('click', function() {
        saveEditedPost(contentInput.value, titleInput.value);
      });
  }

  function saveEditedPost(content, title) {
    
    let id = document.querySelector('#delete-post').getAttribute('data-post-id');
    sendAjaxRequest('put', '/api/post/' + id, { content: content, title: title }, postEditedHandler);
  }

  function postEditedHandler() {
    if (this.status === 200) {
      console.log('Post edited successfully');

      let saveButton = document.querySelector('button#save-post');
      saveButton.style.display = 'none';

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
      console.log('Failed to edit the post. Please try again.');
    }
  }

  function sendDeletePostRequest() {
    let deleteButton = document.querySelector('#delete-post');
    let id = deleteButton.getAttribute('data-post-id');
    sendAjaxRequest('delete', '/api/post/' + id, null, postDeletedHandler);
  }


  function sendDeleteAdminPostRequest(event) {
    event.preventDefault();

    let deleteButton = document.querySelector('button.btn-danger');
    let id = deleteButton.getAttribute('data-post-id');
    console.log(id);
    sendAjaxRequest('delete', '/api/post/' + id, null, adminPostDeleteHandler);
}

  
    
  function sendDeleteFriendRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
    sendAjaxRequest('delete', '/friends/' + id +'/remove', null, friendDeletedHandler);
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
      sendAjaxRequestFormData('post', '/dashboard/create', data, postAddedHandler);
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

  function adminPostDeleteHandler() {
    if (this.status != 200) window.location = '/admin';
    else {
      console.log("Erro");
      console.log(this.status);
    }
    let post = JSON.parse(this.responseText);
    
    console.log(post);

    let tr = document.querySelector('tr[data-post-id="' + post.id + '"]');
    
    tr.remove();
  }
  
  
  function friendDeletedHandler() {
    if (this.status != 200) console.log(this.status); //window.location = '/';
    let friend = JSON.parse(this.responseText);
    console.log(friend);

    let article = document.querySelector('article.friend[data-id="' + friend.friend_id + '"]');
    console.log(article);
    article.remove();
    console.log("deleted friend");
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
    let mediaElement = '';
    let currentURLhost = window.location.host;
    let currentURLprotocol = window.location.protocol;
    let currentURL = currentURLprotocol + '//' + currentURLhost;
    let extension = '';
 
    if (typeof post.post.user.img === 'string' && post.post.user.img.length > 0) {
      avatarHTML = `<img src="${currentURL}/profile/${post.post.user.img}" class="avatar">`;
    }
    else{
      avatarHTML = `<img src="${currentURL}/profile/default.jpg" class="avatar">`;
    }

    
    if (typeof post.file === 'string' && post.file.length > 0) {
      extension = post.file.split('.').pop();
      // Create a new element based on the file type
      
      if (['mp4', 'webm', 'ogg'].includes(extension)) {
        mediaElement = document.createElement('video');
        mediaElement.setAttribute('controls', '');
        let source = document.createElement('source');
        source.src = currentURL + '/post/' + post.file;
        source.type = `video/${extension}`;
        mediaElement.appendChild(source);
      } else if (['mp3', 'wav', 'ogg'].includes(extension)) {
        mediaElement = document.createElement('audio');
        mediaElement.setAttribute('controls', '');
        let source = document.createElement('source');
        source.src = currentURL + '/post/'+post.file;
        source.type = `audio/${extension}`;
        mediaElement.appendChild(source);
      } else {
        mediaElement = `<img src="${currentURL}/post/${post.file}" class="post-image">`;
      }
    }

    console.log(mediaElement);
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
        <button type="submit" class="like-count" data-post-id="${post.post.id}">
          <span class="far fa-heart"></span> 0
        </button>
        <span class="far fa-comment me-1 fa-lg"></span> 0
      </div>
    `;

    // Get the like button in the new post
    let likeButton = new_post.querySelector('button.like-count');
    likeButton.addEventListener('click', sendLikeRequest);


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
    li.classList = "sent-message";
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
        ${comment.user.name}
      </a>
      <span class="float-end">Just now</span>
      <li class="list-group-item">${comment.comment.content}</li>

        <div class="like-comment">
          <button type="submit" class="like-comment animate" data-comment-id="${comment.comment.id}">
                <span class="far fa-heart"></span> 0
          </button>
        </div>

        <div class="float-end" style="padding-top: 10px;">
          <button id="edit-comment" data-id="${comment.comment.id}" class="btn btn-sm btn-primary">Edit</button>
          <button id="delete-comment" data-id="${comment.comment.id}" class="btn btn-sm btn-danger">Delete</button>
        </div>
    `;
   
    // Get the like-comment button in the new comment
    let likeCommentButton = new_comment.querySelector('button.like-comment');

    // Add an event listener to the like-comment button
    likeCommentButton.addEventListener('click', sendLikeCommentRequest);

    return new_comment;
  }
 
  // ########## LIKE AND UNLIKE COMMENTS  ##############

  // Event listener for like comment button

   
    let likeCommentButtons = document.querySelectorAll('button.like-comment');
    [].forEach.call(likeCommentButtons, function(likeCommentButton) {
        likeCommentButton.addEventListener('click', sendLikeCommentRequest);
    });
    


  function sendLikeCommentRequest(event) {
    let id = this.closest('div.comment-container').getAttribute('data-id');
    let likeCommentButton = this;
    if (likeCommentButton.classList.contains('liked')) {
      sendAjaxRequest('delete', '/api/comment/'+id+'/unlike', null, unlikeCommentHandler);
    } else {
      sendAjaxRequest('post', '/api/comment/'+id+'/like', null, likeCommentHandler);
    }
    event.preventDefault();
  }

  function likeCommentHandler() {
    if (this.status == 200) {
      let response = JSON.parse(this.responseText);
      
      let likeCommentButton = document.querySelector('button.like-comment[data-comment-id="'+response.id+'"]');
      if (response.isLiked) {
        console.log(likeCommentButton);
        likeCommentButton.classList.add('liked');
        likeCommentButton.classList.add('animate');
        likeCommentButton.innerHTML = '<span class="fas fa-heart"></span> ' + response.likeCount;
      }
    }
    else {
      console.error('An error occurred: ' + this.status);
      console.error('Response: ' + this.responseText);
    }
  }

  function unlikeCommentHandler() {
    if (this.status == 200) {
      let response = JSON.parse(this.responseText);
      
      let unlikeCommentButton = document.querySelector('button.like-comment[data-comment-id="'+response.id+'"]');
      console.log(unlikeCommentButton);
      if (!response.isLiked) {
        unlikeCommentButton.classList.remove('liked');
        unlikeCommentButton.classList.add('animate');
        unlikeCommentButton.innerHTML = '<span class="far fa-heart"></span> ' + response.likeCount;
      }
    }
    else {
      console.error('An error occurred: ' + this.status);
      console.error('Response: ' + this.responseText);
    }
  }


  // ########## EDIT AND DELETE COMMENTS  ##############

// Event listener for delete comment button
document.addEventListener('click', function(event) {
  if (event.target.id === 'delete-comment') {
    console.log('Delete comment button clicked');
    let commentId = event.target.closest('.comment-container').getAttribute('data-id');
    sendDeleteCommentRequest(commentId);
  }
});

// Event listener for edit comment button
document.addEventListener('click', function(event) {
  if (event.target.id === 'edit-comment') {
    let commentId = event.target.closest('.comment-container').getAttribute('data-id');
    editComment(commentId);
  }
});

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
    saveButton.setAttribute('data-id', commentId);
    saveButton.classList.add('btn', 'btn-sm', 'btn-primary');
    saveButton.id = 'save-comment';

 
    saveButton.addEventListener('click', function() {
      sendEditCommentRequest(commentId, inputElement.value);
    });

    // Replace the edit button with the save button
    let editButton = commentElement.querySelector('button.btn-primary');
    editButton.replaceWith(saveButton);
  }

  function sendEditCommentRequest(commentID,content) {
    console.log('Send edit comment request called.');
    console.log('Comment ID: ' + commentID);
    console.log('Comment content: ' + content);
    sendAjaxRequest('put', '/api/comment/' + commentID, {content: content}, commentEditedHandler);
  }

  function commentEditedHandler() {
    console.log('Comment edited handler called.');
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
    editButton.id = 'edit-comment';
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
    
    if (searchResults.groups.length > 0) {
      gotResults = true;
      let groupsHeader = document.createElement('h2');
      groupsHeader.textContent = 'Groups';
      searchResultContainer.appendChild(groupsHeader);

      searchResults.groups.forEach(group => {
          let link = document.createElement('a');
          link.href = '/groups/' + group.id;
          link.textContent = group.name;

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

// GROUP MESSAGE STUFF
function sendCreateGroupMessageRequest(event){
  let id = this.closest('article').getAttribute('data-id');
  let name = this.querySelector('input[name=content]').value;
  if (name != '')
    sendAjaxRequest('post', '/groups/'+id+'/chat', {content: name}, groupmessageAddedHandler);

  event.preventDefault();
}

function groupmessageAddedHandler() {
  if (this.status != 200) window.location = '/';
  let message = JSON.parse(this.responseText);
  console.log(message);

  let new_message = createGroupMessage(message);
  let messagesList = document.querySelector('ul.messages');
  let li = document.createElement('li');
  li.textContent = ''; // You may want to adjust this line based on your requirements
  li.appendChild(new_message); // Append the new_message element, not the message string
  messagesList.appendChild(li);

  messagesList.scrollTop = messagesList.scrollHeight;

  let form = document.querySelector('article.message form.new_group_message');
  form.querySelector('[type=text]').value = "";
}


function createGroupMessage(message) {
  let new_message = document.createElement('article');
  new_message.classList.add('message');

  let li = document.createElement('li');
  li.classList = 'sent-message';
  li.style.border = 'none';
  li.style.margin = '0';
  li.innerHTML = `<span class="message-info">${message.user}:</span> ${message.content} <span class="float-end small-date">Just now</span>`;

  new_message.appendChild(li);
  return new_message;
}



  function sendBanRequest(event){

    let id = this.closest('tr').getAttribute('data-id');
    console.log(id);

    sendAjaxRequest('post', '/moderator/ban/' + id, null, banHandler);

    console.log('Sent Ban request');
    
    event.preventDefault();
  }

  function sendUnbanRequest(event){

    let id = this.closest('tr').getAttribute('data-id');
    
    console.log(id);

    sendAjaxRequest('delete', '/moderator/unban/' + id, null, unbanHandler);
    
    console.log('Sent unban request');
  }

  function banHandler() {
    if (this.status !== 200) {
        window.location = '/';
        return;
    }

    let id = JSON.parse(this.responseText);

    let oldButton = document.querySelector('button[data-id="' + id + '"]');

    let newButton = document.createElement('button');
    newButton.id = "unban";
    newButton.setAttribute('data-id', id);
    newButton.type = 'submit';
    newButton.classList.add('btn', 'btn-dark');
    newButton.textContent = 'Unban';

    oldButton.parentNode.replaceChild(newButton, oldButton);

    let unbanButtons = document.querySelectorAll('button#unban');
        unbanButtons.forEach(function(button) {
          button.addEventListener('click', sendUnbanRequest);
      });
}
      

  function unbanHandler(){
    if (this.status != 200) window.location = '/';
    
    let id = JSON.parse(this.responseText);

    let oldButton = document.querySelector('button[data-id="' + id + '"]');

    let newButton = document.createElement('button');
    newButton.id = "ban";
    newButton.setAttribute('data-id', id);
    newButton.type = 'submit';
    newButton.classList.add('btn', 'btn-danger');
    newButton.textContent = 'Ban';

    oldButton.parentNode.replaceChild(newButton, oldButton);

    let banButtons = document.querySelectorAll('button#ban');
        banButtons.forEach(function(button) {
          button.addEventListener('click', sendBanRequest);
      });
  }

  function sendCreateModeratorRequest(event){

    let id = this.closest('article').getAttribute('data-id');
    

    sendAjaxRequest('post', '/moderator/create/' + id, null, moderatorCreateHandler);
    
    event.preventDefault();
  }

  function sendDeleteModeratorRequest(event){

    let id = this.closest('article').getAttribute('data-id');
    
    sendAjaxRequest('delete', '/moderator/remove/' + id, null, moderatorDeleteHandler);
  }

  function moderatorCreateHandler() {
    if (this.status != 200) window.location = '/';
    
    let article = document.querySelector('article.set_moderator'); 

    let id = article.getAttribute('data-id');

    let newButton = document.createElement('button');
    newButton.setAttribute('data-id', id);
    newButton.type = 'submit';
    newButton.classList.add('remove_moderator');
    newButton.textContent = 'Remove Moderator Role';
    
    let existingButton = article.querySelector('button.add_moderator');

    existingButton.parentNode.replaceChild(newButton, existingButton);

    let removeModerator = document.querySelector('article.set_moderator button.remove_moderator');
    if (removeModerator != null)
      removeModerator.addEventListener('click',sendDeleteModeratorRequest);

  }

  function moderatorDeleteHandler() {
    if (this.status != 200) window.location = '/';
    
    let article = document.querySelector('article.set_moderator'); 

    let id = article.getAttribute('data-id');

    let newButton = document.createElement('button');
    newButton.setAttribute('data-id', id);
    newButton.type = 'submit';
    newButton.classList.add('add_moderator');
    newButton.textContent = 'Assign Moderator Role';
    
    let existingButton = article.querySelector('button.remove_moderator');


    existingButton.parentNode.replaceChild(newButton, existingButton);

    let createModerator = document.querySelector('article.set_moderator button.add_moderator');
    if (createModerator != null)
      createModerator.addEventListener('click',sendCreateModeratorRequest);
}



  addEventListeners();
  

  
  