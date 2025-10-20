// Add styles for chat features
const style = document.createElement('style');
style.textContent = `
    .chat-search-box {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
        width: 80%;
        max-width: 500px;
    }

    .search-header {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .search-header input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .search-header button {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }

    .search-results {
        max-height: 300px;
        overflow-y: auto;
    }

    .report-box {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
        width: 80%;
        max-width: 500px;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .report-header button {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }

    .report-content {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .report-content select,
    .report-content textarea {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .report-content textarea {
        min-height: 100px;
        resize: vertical;
    }

    .report-content button {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 4px;
        cursor: pointer;
    }

    .report-content button:hover {
        background: #0056b3;
    }
`;
document.head.appendChild(style);

document.addEventListener('DOMContentLoaded', function () {
    console.log("Tabs script loaded ✅");

    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));

        tab.classList.add('active');
        const tabId = tab.getAttribute('data-tab');
        const target = document.getElementById(tabId);
        if (target) target.classList.add('active');
      });
    });
  });

function submitVote(el) {
    const pollId = $(el).data('poll-id');
    const optionId = $(el).data('option-id');

    if ($(el).hasClass('voted')) return;

    $.ajax({
        url: `/polls/${pollId}/vote`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        data: {
            option_id: optionId
        },
        success: function(data) {
            if (data.status === 'success') {
                location.reload(); // می‌تونی اینو با آپدیت داینامیک هم جایگزین کنی
            } else {
              showErrorAlert(data.message || 'خطا در ثبت رأی');
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ خطا در اتصال:', error);
            showErrorAlert('خطا در اتصال به سرور');
        }
    });
}

$(document).ready(function() {
  $('#manager_vote').select2({
    multiple: true,

    placeholder: "انتخاب کنید",
    dir: "rtl",
  });
  $('#inspector_vote').select2({
    multiple: true,

    placeholder: "انتخاب کنید",
    dir: "rtl",
  });
  $('#options').select2({
    placeholder: "انتخاب کنید",
    dir: "rtl",
    tags: 'true'
  });

  $('#electionForm').on('submit', function (e) {
    const inspectorSelectedCount = $('#inspector_vote').val()?.length || 0;
    const managerSelectedCount   = $('#manager_vote').val()?.length || 0;
  
    console.log(`بازرس: ${inspectorSelectedCount}, مدیر: ${managerSelectedCount}`);
  
    if (inspectorSelectedCount > inspectorCount) {
      e.preventDefault();
      showWarningAlert(`برای تعداد بازرس دقیقاً ${inspectorCount} گزینه انتخاب کنید.`);
      return;
    }
  
    if (managerSelectedCount > manageCount) {
      e.preventDefault();
      showWarningAlert(`برای تعداد مدیر دقیقاً ${manageCount} گزینه انتخاب کنید.`);
      return;
    }
  
    // همه‌چیز اوکی => فرم ارسال می‌شه
  });
  

  

});

function openElectionBox(){
  document.querySelector('.election-box').style='display: flex'
  document.querySelector('#back').style='display: block'
  closeGroupInfo()
}

function closeElectionBox(){
  document.querySelector('.election-box').style='display: none'
  document.querySelector('#back').style='display: none'
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.reaction-buttons').forEach(container => {
    const blogId = container.dataset.postId;
    const likeBtn = container.querySelector('.btn-like');
    const dislikeBtn = container.querySelector('.btn-dislike');

    likeBtn.addEventListener('click', () => {
      console.log('✅ لایک کلیک شد برای', blogId);
      sendReaction(blogId, '1', container);
    });

    dislikeBtn.addEventListener('click', () => {
      console.log('✅ دیسلایک کلیک شد برای', blogId);
      sendReaction(blogId, '0', container);
    });
  });
});

  
function sendReaction(blogId, type, container) {
  $.ajax({
    url: `/blogs/${blogId}/react`,
    method: 'POST',
    data: {
      type: type,
      _token: document.querySelector('meta[name="csrf-token"]').content
    },
    success: function (data) {
      if (data.status === 'success') {
        // بروزرسانی تعداد لایک/دیسلایک
        $(container).find('.like-count').text(data.likes);
        $(container).find('.dislike-count').text(data.dislikes);

        // تغییر کلاس‌ برای حالت فعال یا غیرفعال
        const likeBtn = $(container).find('.btn-like');
        const dislikeBtn = $(container).find('.btn-dislike');

        if (type === '1') {
          likeBtn.toggleClass('active');
          dislikeBtn.removeClass('active');
        } else {
          dislikeBtn.toggleClass('active');
          likeBtn.removeClass('active');
        }
      } else {
        showErrorAlert(data.message || 'خطا در ثبت واکنش');
      }
    },
    error: function () {
      showErrorAlert('❌ خطا در ارتباط با سرور');
    }
  });
}



function openGroupInfo() {
  if (window.innerWidth < 768) {
    document.getElementById('groupInfoPanel').style.right = '0';
  }
}

function closeGroupInfo() {
  if (window.innerWidth < 768) {
    document.getElementById('groupInfoPanel').style.right = '-100%';
  } else {
    document.getElementById('groupInfoPanel').style.right = '0';
  }
}


function openBlogBox(){
    document.querySelector('#back').style='display: block'
    document.querySelector('#postFormBox').style='display: block'
  }

  

  function openPollBox(){
    document.querySelector('#back').style='display: block'
    document.querySelector('#pollOptionsBox').style='display: block'
  }

  function openElection2Box(){
    document.querySelector('#back').style='display: block'
    document.querySelector('#electionOptionsBox').style='display: block'
  }

  
  let openSkillListId = null;

  function toggleSkillList(pollId) {
      closeAllModals();
  
      const box = document.getElementById('skill-list-' + pollId);
      const back = document.getElementById('back');


      if (box && box.style.display !== 'flex') {
          box.style.display = 'flex';
          back.style.display = 'block';
          openSkillListId = pollId;
      } else {
          openSkillListId = null;
      }
  }


  
  function closeSkill() {
      document.querySelectorAll('.skill-list').forEach(el => el.style.display = 'none');
      const back = document.getElementById('back');
      if (back) back.style.display = 'none';
      openSkillListId = null;
  }
  
  // بعد از AJAX
  function reapplySkillListState() {
      if (openSkillListId !== null) {
          const box = document.getElementById('skill-list-' + openSkillListId);
          const back = document.getElementById('back');
          if (box) {
              box.style.display = 'flex';
          }
          if (back) {
              back.style.display = 'block';
          }
      }
  }
  
  
  
  function cancelPostForm(){
    document.querySelector('#back').style='display: none'
    document.querySelector('#postFormBox').style='display: none'
  }

  function cancelPollForm(){
    document.querySelector('#back').style='display: none'
    document.querySelector('#pollOptionsBox').style='display: none'
  }
  
  function cancelelectionForm(){
    document.querySelector('#back').style='display: none'
    document.querySelector('#electionOptionsBox').style='display: none'
  }
  
    window.addEventListener('DOMContentLoaded', function () {
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
});
    

    setInterval(function() {
    $.ajax({
        url: '/api/groups/' + groupId + '/messages',
        method: 'GET',
        success: function(data) {
            // Store the current scroll position
            const chatBox = document.getElementById('chat-box');
            const isScrolledToBottom = chatBox.scrollHeight - chatBox.scrollTop === chatBox.clientHeight;
            
            // Store the current edit form state if it exists
            const activeEditForm = document.querySelector('.edit-form[style*="display: block"]');
            const activeEditFormId = activeEditForm ? activeEditForm.id : null;
            const activeEditContent = activeEditForm ? document.getElementById(`edit-message-${activeEditFormId.split('-')[2]}`).value : null;
            
            // Parse the new messages
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data;
            const newMessages = tempDiv.querySelectorAll('.message-wrapper');
            
            // Get existing message IDs
            const existingMessageIds = new Set();
            document.querySelectorAll('.message-wrapper').forEach(msg => {
                existingMessageIds.add(msg.getAttribute('data-message-id'));
            });
            
            // Append only new messages
            newMessages.forEach(msg => {
                const messageId = msg.getAttribute('data-message-id');
                if (!existingMessageIds.has(messageId)) {
                    chatBox.appendChild(msg);
                }
            });
            
            reapplySkillListState();
            startPollCountdowns();

            // Restore the edit form state if it existed
            if (activeEditFormId && activeEditContent) {
                const editForm = document.getElementById(activeEditFormId);
                const editInput = document.getElementById(`edit-message-${activeEditFormId.split('-')[2]}`);
                if (editForm && editInput) {
                    editForm.style.display = 'block';
                    editInput.value = activeEditContent;
                    editInput.focus();
                }
            }

            // Scroll to bottom if user was already at the bottom
            if (isScrolledToBottom) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }
    });
}, 3000);


document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');
    const form = document.getElementById('chatForm');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const parentId = document.getElementById('parent_id').value;
            
            if (parentId) {
                formData.append('parent_id', parentId);
            }
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server did not return JSON');
                }

                const responseData = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        const errorMessage = responseData.message || 'خطا در اعتبارسنجی داده‌ها';
                        const errors = responseData.errors ? Object.values(responseData.errors).flat().join('\n') : '';
                        alert(`${errorMessage}\n${errors}`);
                    } else if (response.status === 500) {
                        console.error('Server Error Details:', responseData);
                        alert('خطا در سرور. لطفاً دوباره تلاش کنید. اگر مشکل ادامه داشت، با پشتیبانی تماس بگیرید.');
                    } else {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                }
                
                if (responseData.status === 'success') {
                    appendMessage(responseData.message);
                    form.reset();
                    // Clear parent_id after successful submission
                    document.getElementById('parent_id').value = '';
                    // Hide reply indicator
                    const replyIndicator = document.querySelector('.reply-indicator');
                    if (replyIndicator) {
                        replyIndicator.remove();
                    }
                } else {
                    alert('خطا در ارسال پیام: ' + (responseData.message || 'خطای ناشناخته'));
                }
            } catch (error) {
                console.error('Error:', error);
                if (error.message.includes('Failed to fetch')) {
                    alert('خطا در اتصال به سرور. لطفاً اتصال اینترنت خود را بررسی کنید.');
                } else {
                    alert('خطا در ارسال پیام. لطفاً دوباره تلاش کنید.');
                }
            }
        });
    }
});

function appendMessage(message) {
    const chatBox = document.getElementById('chat-box');
    if (!chatBox) return;

    const messageWrapper = document.createElement('div');
    messageWrapper.className = 'message-wrapper ' + (message.user_id == authUserId ? 'you' : 'other');
    messageWrapper.setAttribute('data-message-id', message.id);
    messageWrapper.id = `msg-${message.id}`;
    
    let messageHtml = '';
    
    if (message.user_id != authUserId) {
        messageHtml += `
            <div class="group-avatar" style="width: 2rem; height: 2rem; font-size: .6rem; margin: 0;">
                <span>${message.sender.split(' ').map(n => n.charAt(0)).join(' ')}</span>
            </div>
        `;
    }
    
    messageHtml += `
        <div class="message-content" style="padding: 0">
            ${message.user_id != authUserId ? `<div class="message-sender" style="margin-left: .4rem">${message.sender}</div>` : ''}
            <div class="message-bubble">
                ${message.parent_id ? `
                    <div class="reply-box" style="border-right: 2px solid #666; padding-right: 8px; margin-bottom: 4px;">
                        <div class="group-avatar" style="width: 1.5rem; height: 1.5rem; font-size: .6rem; margin: 0;">
                            <span>${message.parent_sender.split(' ').map(n => n.charAt(0)).join(' ')}</span>
                        </div>
                        <div style="display: inline-block; vertical-align: middle;">
                            <div style="font-weight: bold; font-size: .9rem;">${message.parent_sender}</div>
                            <div style="font-size: .8rem; color: #666;">${message.parent_content}</div>
                        </div>
                    </div>
                ` : ''}
                ${message.file_path ? `
                    <div class="file-message">
                        ${message.file_type.startsWith('image/') ? `
                            <img src="/${message.file_path}" alt="${message.file_name}" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                        ` : message.file_type.startsWith('audio/') ? `
                            <audio controls style="width: 100%;">
                                <source src="/${message.file_path}" type="${message.file_type}">
                                مرورگر شما از پخش صدا پشتیبانی نمی‌کند.
                            </audio>
                        ` : `
                            <a href="/${message.file_path}" download="${message.file_name}" class="file-download">
                                <i class="fas fa-file-download"></i>
                                ${message.file_name}
                            </a>
                        `}
                    </div>
                ` : ''}
                ${message.voice_message ? `
                    <div class="voice-message">
                        <audio controls style="width: 100%;">
                            <source src="${message.voice_message}" type="audio/wav">
                            مرورگر شما از پخش صدا پشتیبانی نمی‌کند.
                        </audio>
                    </div>
                ` : ''}
                ${message.message ? `<p>${message.message}</p>` : ''}
            </div>
        </div>
    `;
    
    messageWrapper.innerHTML = messageHtml;
    chatBox.appendChild(messageWrapper);
    
    // Scroll to the bottom of the chat
    chatBox.scrollTop = chatBox.scrollHeight;
}

function closeAllModals() {
  closeElectionBox(); 
  cancelPostForm();
  cancelPollForm()
  closeSkill()
  cancelelectionForm()
}

function startPollCountdowns() {
  document.querySelectorAll('.poll-timer').forEach(timer => {
    if (timer.dataset.timerSet === "true") return;

    const expiresAtStr = timer.getAttribute('data-expires');
    if (!expiresAtStr) {
      timer.innerText = 'بدون زمان پایان';
      return;
    }

    const expiresAt = new Date(expiresAtStr);

    function updateTimer() {
      const now = new Date();
      const diffMs = expiresAt - now;

      if (isNaN(diffMs)) {
        timer.innerText = 'تاریخ نامعتبر';
        return;
      }

      if (diffMs <= 0) {
        timer.innerText = 'پایان یافته';
        return;
      }

      const totalSeconds = Math.floor(diffMs / 1000);
      const hours = Math.floor(totalSeconds / 3600);
      const minutes = Math.floor((totalSeconds % 3600) / 60);
      const seconds = totalSeconds % 60;
if (hours > 24) {
    const days = Math.floor(hours / 24);
    const remainingHours = hours % 24;

    timer.innerText = `${days} روز ${remainingHours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
} else {
    timer.innerText = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

    }

    updateTimer();
    setInterval(updateTimer, 1000);

    timer.dataset.timerSet = "true";
  });
}

// Voice recording functionality
let mediaRecorder;
let audioChunks = [];
let isRecording = false;

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const voiceRecordBtn = document.getElementById('voice-record-btn');
    const stopRecordingBtn = document.getElementById('stop-recording');

    if (voiceRecordBtn) {
        voiceRecordBtn.addEventListener('click', startRecording);
    }

    if (stopRecordingBtn) {
        stopRecordingBtn.addEventListener('click', stopRecording);
    }
});

async function startRecording() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        
        
        mediaRecorder.ondataavailable = (event) => {
            audioChunks.push(event.data);
        };
        
        mediaRecorder.onstop = async () => {
            const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
            const reader = new FileReader();
            reader.readAsDataURL(audioBlob);
            reader.onloadend = async () => {
                const voiceMessageInput = document.getElementById('voice_message');
                if (voiceMessageInput) {
                    voiceMessageInput.value = reader.result;
                    
                    const form = document.getElementById('chatForm');
                    if (form) {
                        const formData = new FormData(form);
                        // Add an empty message field to satisfy server validation
                        formData.append('message', '[پیام صوتی]');
                        
                        try {
                            const response = await fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });

                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error('Server did not return JSON');
                            }

                            const responseData = await response.json();

                            if (!response.ok) {
                                if (response.status === 422) {
                                    const errorMessage = responseData.message || 'خطا در اعتبارسنجی داده‌ها';
                                    const errors = responseData.errors ? Object.values(responseData.errors).flat().join('\n') : '';
                                    alert(`${errorMessage}\n${errors}`);
                                } else if (response.status === 500) {
                                    console.error('Server Error Details:', responseData);
                                    alert('خطا در سرور. لطفاً دوباره تلاش کنید. اگر مشکل ادامه داشت، با پشتیبانی تماس بگیرید.');
                                } else {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                            }
                            
                            if (responseData.status === 'success') {
                                appendMessage(responseData.message);
                                form.reset();
                            } else {
                                alert('خطا در ارسال پیام صوتی: ' + (responseData.message || 'خطای ناشناخته'));
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            if (error.message.includes('Failed to fetch')) {
                                alert('خطا در اتصال به سرور. لطفاً اتصال اینترنت خود را بررسی کنید.');
                            } else {
                                alert('خطا در ارسال پیام صوتی. لطفاً دوباره تلاش کنید.');
                            }
                        }
                    }
                }
            };
        };
        
        mediaRecorder.start();
        isRecording = true;
        
        const voiceRecording = document.getElementById('voice-recording');
        const voiceRecordBtn = document.getElementById('voice-record-btn');
        
        if (voiceRecording) {
            voiceRecording.style.display = 'flex';
        }
        if (voiceRecordBtn) {
            voiceRecordBtn.style.display = 'none';
        }
        
    } catch (err) {
        console.error('Error accessing microphone:', err);
        alert('دسترسی به میکروفون امکان‌پذیر نیست. لطفاً دسترسی را بررسی کنید.');
    }
}

function stopRecording() {
    if (isRecording && mediaRecorder) {
        mediaRecorder.stop();
        isRecording = false;
        
        // Stop all tracks
        mediaRecorder.stream.getTracks().forEach(track => track.stop());
        
        // Hide recording UI
        const voiceRecording = document.getElementById('voice-recording');
        const voiceRecordBtn = document.getElementById('voice-record-btn');
        
        if (voiceRecording) {
            voiceRecording.style.display = 'none';
        }
        if (voiceRecordBtn) {
            voiceRecordBtn.style.display = 'block';
        }
    }
}

// Add click handler for reply
document.querySelectorAll('.message-bubble').forEach(bubble => {
    bubble.addEventListener('click', function(e) {
        if (e.target.closest('.reply-box')) return;
        
        const messageId = this.closest('.message-wrapper').dataset.messageId;
        document.getElementById('parent_id').value = messageId;
    });
});

function replyToMessage(messageId, senderName, content) {
    // Create reply indicator
    const replyIndicator = document.createElement('div');
    replyIndicator.className = 'reply-indicator';
    replyIndicator.innerHTML = `
        <div class="reply-info">
            <div class="group-avatar">
                <span>${senderName.split(' ').map(n => n.charAt(0)).join(' ')}</span>
            </div>
            <div class="reply-content">${content}</div>
        </div>
        <button class="btn-cancel-reply" onclick="cancelReply()">
            <i class="fas fa-times"></i>
        </button>
    `;

    // Remove existing reply indicator if any
    const existingIndicator = document.querySelector('.reply-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }

    // Add new reply indicator
    document.body.appendChild(replyIndicator);

    // Set parent_id in form
    const parentIdInput = document.getElementById('parent_id');
    if (parentIdInput) {
        parentIdInput.value = messageId;
    }

    // Scroll to input
    const chatForm = document.getElementById('chatForm');
    if (chatForm) {
        chatForm.scrollIntoView({ behavior: 'smooth' });
    }
}

function cancelReply() {
    // Remove reply indicator
    const replyIndicator = document.querySelector('.reply-indicator');
    if (replyIndicator) {
        replyIndicator.remove();
    }

    // Clear parent_id
    const parentIdInput = document.getElementById('parent_id');
    if (parentIdInput) {
        parentIdInput.value = '';
    }
}

// Add event listener for form submit to clear reply after sending
const chatForm = document.getElementById('chatForm');
if (chatForm) {
    chatForm.addEventListener('submit', function() {
        setTimeout(cancelReply, 100);
    });
}

// // Add click handlers for file upload buttons
// document.getElementById('file-upload').addEventListener('change', function(e) {
//     if (this.files.length > 0) {
//         document.getElementById('chatForm').submit();
//     }
// });

function editMessage(messageId, currentContent) {
    // Hide all other edit forms
    document.querySelectorAll('.edit-form').forEach(form => {
        form.style.display = 'none';
    });
    
    // Show the edit form for this message
    const editForm = document.getElementById(`edit-form-${messageId}`);
    const editInput = document.getElementById(`edit-message-${messageId}`);
    
    if (editForm && editInput) {
        editForm.style.display = 'block';
        editInput.focus();
    }
}

function cancelEdit(messageId) {
    const editForm = document.getElementById(`edit-form-${messageId}`);
    if (editForm) {
        editForm.style.display = 'none';
    }
}


function submitEdit(event, messageId) {
    event.preventDefault();
    

    const newContent = document.getElementById(`edit-message-${messageId}`).value;
    
    $.ajax({
        url: `/messages/${messageId}/edit`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        data: {
            content: newContent
        },
        success: function(response) {
            if (response.status === 'success') {
                // Update the message content
                const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                const messageContent = messageElement.querySelector('.message-bubble p');
                if (messageContent) {
                    messageContent.textContent = newContent;
                }
                
                // Add edited badge if it doesn't exist
                if (!messageElement.querySelector('.edited-badge')) {
                    const editedBadge = document.createElement('span');
                    editedBadge.className = 'edited-badge';
                    editedBadge.textContent = 'ویرایش شده';
                    messageContent.parentNode.insertBefore(editedBadge, messageContent.nextSibling);
                }
                
                // Hide the edit form
                document.getElementById(`edit-form-${messageId}`).style.display = 'none';
                
                // Show success message
                alert('پیام با موفقیت ویرایش شد');
            } else {
                alert(response.message || 'خطا در ویرایش پیام');
            }
        },
        error: function() {
            alert('خطا در ارتباط با سرور');
        }
    });
}

function reportMessage(messageId) {
    const reason = prompt('لطفاً دلیل گزارش این پیام را وارد کنید:');
    if (reason) {
        fetch(`/groups/messages/${messageId}/report`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('پیام با موفقیت گزارش شد.');
            } else {
                alert('خطا در گزارش پیام.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطا در گزارش پیام.');
        });
    }
}

function deletePost(postId) {
    if (confirm('آیا از حذف این پست اطمینان دارید؟')) {
        $.ajax({
            url: `/blog/${postId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            success: function(response) {
                if (response.status === 'success') {
                    location.reload();
                } else {
                    alert(response.message || 'خطا در حذف پست');
                }
            },
            error: function() {
                alert('خطا در ارتباط با سرور');
            }
        });
    }
}

function editPost(postId, currentTitle, currentContent, currentCategoryId) {
    // Hide all other edit forms
    document.querySelectorAll('.post-edit-form').forEach(form => {
        form.style.display = 'none';
    });
    
    // Show the edit form for this post
    const editForm = document.getElementById(`post-edit-form-${postId}`);
    const titleInput = document.getElementById(`edit-post-title-${postId}`);
    const contentInput = document.getElementById(`edit-post-content-${postId}`);
    const categorySelect = document.getElementById(`edit-post-category-${postId}`);
    
    if (editForm && titleInput && contentInput && categorySelect) {
        editForm.style.display = 'block';
        titleInput.value = currentTitle;
        contentInput.value = currentContent;
        categorySelect.value = currentCategoryId;
        titleInput.focus();
    }
}

function cancelPostEdit(postId) {
    const editForm = document.getElementById(`post-edit-form-${postId}`);
    if (editForm) {
        editForm.style.display = 'none';
    }
}

async function submitPostEdit(event, postId) {
    event.preventDefault();
    
    const title = document.getElementById(`edit-post-title-${postId}`).value;
    const content = document.getElementById(`edit-post-content-${postId}`).value;
    const categoryId = document.getElementById(`edit-post-category-${postId}`).value;
    
    // Validate required fields
    if (!title.trim()) {
        alert('لطفاً عنوان پست را وارد کنید');
        return;
    }
    if (!content.trim()) {
        alert('لطفاً محتوای پست را وارد کنید');
        return;
    }
    if (!categoryId) {
        alert('لطفاً دسته‌بندی را انتخاب کنید');
        return;
    }
    
    try {
        const response = await fetch(`/blog/${postId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                title: title,
                content: content,
                category_id: categoryId
            })
        });

        const data = await response.json();

        if (!response.ok) {
            if (response.status === 422) {
                const errors = data.errors;
                let errorMessage = '';
                for (const field in errors) {
                    errorMessage += errors[field].join('\n') + '\n';
                }
                console.log(errorMessage)
            } else {
                throw new Error(data.message || 'خطا در ویرایش پست');
            }
            return;
        }

        if (data.status === 'success') {
            location.reload();
        } else {
            alert(data.message || 'خطا در ویرایش پست');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('خطا در ارتباط با سرور');
    }
}

function openChatSearch() {
    const searchBox = document.createElement('div');
    searchBox.className = 'chat-search-box';
    searchBox.innerHTML = `
        <div class="search-header">
            <input type="text" id="chatSearchInput" placeholder="جستجو در پیام‌ها...">
            <button onclick="closeChatSearch()">×</button>
        </div>
        <div id="searchResults" class="search-results"></div>
    `;
    document.body.appendChild(searchBox);
    
    const searchInput = document.getElementById('chatSearchInput');
    searchInput.focus();
    
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value.toLowerCase();
            const messages = document.querySelectorAll('.message-wrapper');
            const results = [];
            const seenIds = new Set();
            
            messages.forEach(msg => {
                const messageId = msg.getAttribute('data-message-id');
                if (seenIds.has(messageId)) return;
                seenIds.add(messageId);
                
                const content = msg.querySelector('.message-bubble p')?.textContent.toLowerCase() || '';
                if (content.includes(searchTerm)) {
                    results.push(msg);
                }
            });
            
            const resultsContainer = document.getElementById('searchResults');
            resultsContainer.innerHTML = '';
            
            results.forEach(msg => {
                const clone = msg.cloneNode(true);
                clone.addEventListener('click', () => {
                    msg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    msg.style.backgroundColor = '#ffeb3b';
                    setTimeout(() => msg.style.backgroundColor = '', 2000);
                });
                resultsContainer.appendChild(clone);
            });
        }, 300); // تاخیر 300 میلی‌ثانیه برای جلوگیری از جستجوی مکرر
    });
}

function closeChatSearch() {
    const searchBox = document.querySelector('.chat-search-box');
    if (searchBox) {
        searchBox.remove();
    }
}

function clearChatHistory() {
    if (confirm('آیا از پاک کردن تاریخچه چت اطمینان دارید؟')) {
        fetch(`/api/groups/${groupId}/clear-history`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('chat-box').innerHTML = '';
                alert('تاریخچه چت با موفقیت پاک شد');
            } else {
                alert('خطا در پاک کردن تاریخچه چت');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطا در پاک کردن تاریخچه چت');
        });
    }
}

function deleteChat() {
    if (confirm('آیا از حذف این چت اطمینان دارید؟ این عمل غیرقابل بازگشت است.')) {
        fetch(`/api/groups/${groupId}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/groups';
            } else {
                alert('خطا در حذف چت');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطا در حذف چت');
        });
    }
}

function reportUser() {
    const reportBox = document.createElement('div');
    reportBox.className = 'report-box';
    reportBox.innerHTML = `
        <div class="report-header">
            <h3>گزارش کاربر</h3>
            <button onclick="closeReportBox()">×</button>
        </div>
        <div class="report-content">
            <select id="reportReason">
                <option value="spam">اسپم</option>
                <option value="harassment">آزار و اذیت</option>
                <option value="inappropriate">محتوا نامناسب</option>
                <option value="other">سایر</option>
            </select>
            <textarea id="reportDescription" placeholder="توضیحات بیشتر..."></textarea>
            <button onclick="submitReport()">ارسال گزارش</button>
        </div>
    `;
    document.body.appendChild(reportBox);
}

function closeReportBox() {
    const reportBox = document.querySelector('.report-box');
    if (reportBox) {
        reportBox.remove();
    }
}

function submitReport() {
    const reason = document.getElementById('reportReason').value;
    const description = document.getElementById('reportDescription').value;
    
    fetch(`/api/groups/${groupId}/report`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            reason: reason,
            description: description
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('گزارش با موفقیت ارسال شد');
            closeReportBox();
        } else {
            alert('خطا در ارسال گزارش');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('خطا در ارسال گزارش');
    });
}
