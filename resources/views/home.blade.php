@extends('layouts.homeStyles')

<div class="d-flex" id="wrapper">
   <!-- Sidebar -->
   <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading">Start Bootstrap </div>
      <div class="list-group list-group-flush" id="chatsContainer">
      	@foreach ($chats as $chat)
		    <a class="list-group-item list-group-item-action bg-light chat-item" data-id="{{ $chat->chat_id }}">{{ $chat->name }}</a>
		@endforeach
      </div>
   </div>
   <!-- /#sidebar-wrapper -->
   <!-- Page Content -->
   <div id="page-content-wrapper">
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
         <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>
         <button class="btn btn-success ml-2" data-toggle="modal" data-target=".bd-example-modal-lg">Add chat</button>
         <button class="btn btn-success ml-2" data-toggle="modal" data-target=".bd-example-modal-lg-add-user" id="addUserModal" style="display: none;">Add user</button>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
               <li class="nav-item active">
                  <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="#">Link</a>
               </li>
               <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Dropdown
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                     <a class="dropdown-item" href="#">Action</a>
                     <a class="dropdown-item" href="#">Another action</a>
                     <div class="dropdown-divider"></div>
                     <a class="dropdown-item" href="#">Something else here</a>
                  </div>
               </li>
            </ul>
         </div>
      </nav>
      <div class="chat-content">
      	<div class="messages-content" id="messagesContainer">
            <div class="message-item"  b-for="let message of messages" b-class="message.me ? '' : 'recive'">
               <div b-class="message.me ? 'msg_container_send' : 'msg_container'">
                  {message.text}
               </div>
               <div class="user_cont_msg">
                  <span>{message.user}</span>
               </div>
            </div>
      	</div>
      	<div class="message-input-container">
      		<div contenteditable="true" class="message-input" id="messageText"></div>
      		<div class="input-group-append">
				<button class="btn btn-outline-secondary send-message" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
			</div>
      	</div>
      </div>
   </div>
   <!-- /#page-content-wrapper -->
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;">
   	<div class="modal-dialog modal-lg">
      	<div class="modal-content">
         	<div class="modal-header">
	            <h4 class="modal-title" id="myLargeModalLabel">Add chat</h4>
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            	<span aria-hidden="true" id="closeChatModal">×</span>
	            </button>
        	</div>
        	<div class="modal-body">
				<div class="input-group mb-3">
					<input type="text" class="form-control" id="chatName" placeholder="Chat name" aria-label="Chat name" aria-describedby="basic-addon2" minlength="3" required="true">
					<div class="input-group-append">
						<button class="btn btn-outline-secondary add-chat" type="button">Add</button>
					</div>
					<div  class="error-message-container fade chat-name">
						<small id="passwordHelp" class="text-danger">
							Must be 3 characters long.
						</small>
					</div>
				</div>
        	</div>
      	</div>
   	</div>
</div>

<div class="modal fade bd-example-modal-lg-add-user" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;">
   	<div class="modal-dialog modal-lg">
      	<div class="modal-content">
         	<div class="modal-header">
	            <h4 class="modal-title" id="myLargeModalLabel">Add user</h4>
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            	<span aria-hidden="true" id="closeSearchModal">×</span>
	            </button>
        	</div>
        	<div class="modal-body">
				<div class="input-group mb-3">
					<input type="text" class="form-control" id="userInput" placeholder="User name" aria-label="Chat name" aria-describedby="basic-addon2" minlength="3" required="true">
					<div class="input-group-append">
						<button class="btn btn-outline-secondary add-user-to-chat" type="button">Add</button>
					</div>
					<div  class="error-message-container fade user-name">
						<small id="passwordHelp" class="text-danger">
							Please select an existing user
						</small>
					</div>
					<div class="users-list" id="foundUsers">
						<div class="found-user">Name asd@asd.asd</div>
					</div>
				</div>
        	</div>
      	</div>
   	</div>
</div>

@extends('layouts.homeScripts')

