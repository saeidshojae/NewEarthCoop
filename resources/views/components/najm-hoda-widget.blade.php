{{-- Widget چت نجم‌هدا --}}
<div id="najm-hoda-widget" class="najm-hoda-widget" style="display: block;">
    {{-- دکمه باز/بسته کردن --}}
    <button id="najm-hoda-toggle" class="najm-hoda-toggle-btn" title="چت با نجم‌هدا">
        <i class="fas fa-robot"></i>
        <span class="najm-hoda-notification-badge" id="najm-hoda-badge" style="display: none;">0</span>
    </button>
    
    {{-- کانتینر چت - به صورت پیش‌فرض بسته است --}}
    <div id="najm-hoda-chat-container" class="najm-hoda-chat-container" style="display: none !important;">
        {{-- هدر --}}
        <div class="najm-hoda-header">
            <div class="d-flex align-items-center">
                <div class="najm-hoda-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="flex-grow-1 ms-2">
                    <h6 class="mb-0">نجم‌هدا 🌟</h6>
                    <small class="text-white-50">دستیار هوشمند ارثکوپ</small>
                </div>
                <button id="najm-hoda-close" class="btn-close btn-close-white" title="بستن"></button>
            </div>
            
            {{-- انتخاب عامل --}}
            <div class="najm-hoda-agent-selector mt-2">
                <select id="najm-hoda-agent" class="form-select form-select-sm">
                    <option value="auto">🤖 تشخیص خودکار</option>
                    <option value="engineer">🔧 مهندس</option>
                    <option value="pilot">✈️ خلبان</option>
                    <option value="steward">👨‍✈️ مهماندار</option>
                    <option value="guide">📖 راهنما</option>
                </select>
            </div>
        </div>
        
        {{-- بدنه پیام‌ها --}}
        <div id="najm-hoda-messages" class="najm-hoda-messages">
            {{-- پیام خوش‌آمدگویی --}}
            <div class="najm-hoda-message assistant">
                <div class="najm-hoda-message-avatar">🌟</div>
                <div class="najm-hoda-message-content">
                    <strong>سلام! من نجم‌هدا هستم</strong>
                    <p>نرم‌افزار جامع مدیریت هوشمند دنیای ارثکوپ</p>
                    <p class="mb-1">من یک تیم 4 نفره هستم:</p>
                    <ul class="mb-0 small">
                        <li>🔧 <strong>مهندس</strong>: طراحی و کدنویسی</li>
                        <li>✈️ <strong>خلبان</strong>: مدیریت پروژه</li>
                        <li>👨‍✈️ <strong>مهماندار</strong>: پشتیبانی</li>
                        <li>📖 <strong>راهنما</strong>: استراتژی</li>
                    </ul>
                    <p class="mt-2 mb-0">چطور می‌تونم کمکتون کنم؟</p>
                </div>
            </div>
        </div>
        
        {{-- اندیکاتور در حال تایپ --}}
        <div id="najm-hoda-typing" class="najm-hoda-typing d-none">
            <div class="najm-hoda-message assistant">
                <div class="najm-hoda-message-avatar">🤖</div>
                <div class="najm-hoda-typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        
        {{-- فوتر (ورودی) --}}
        <div class="najm-hoda-footer">
            <div class="input-group">
                <input 
                    type="text" 
                    id="najm-hoda-input" 
                    class="form-control" 
                    placeholder="پیام خود را بنویسید..."
                    autocomplete="off"
                >
                <button id="najm-hoda-send" class="btn btn-primary" type="button">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <div class="najm-hoda-hints mt-2" id="najm-hoda-hints"></div>
        </div>
    </div>
</div>

<style>
/* استایل‌های نجم‌هدا */
.najm-hoda-widget {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 9999;
    font-family: IRANSans, Tahoma, Arial;
}

.najm-hoda-toggle-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #37c4b4 0%, #459f96 100%);
    border: none;
    color: white;
    font-size: 28px;
    box-shadow: 0 4px 12px rgba(55, 196, 180, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.najm-hoda-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(55, 196, 180, 0.6);
}

.najm-hoda-notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4444;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.najm-hoda-chat-container {
    position: absolute;
    bottom: 80px;
    left: 0;
    width: 380px;
    max-width: calc(100vw - 40px);
    height: 600px;
    max-height: calc(100vh - 120px);
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    display: none; /* به صورت پیش‌فرض بسته */
    flex-direction: column;
    overflow: hidden;
}

.najm-hoda-chat-container:not(.d-none) {
    display: flex !important; /* وقتی باز است، flex نمایش بده */
}

.najm-hoda-header {
    background: linear-gradient(135deg, #37c4b4 0%, #459f96 100%);
    color: white;
    padding: 15px;
    border-radius: 16px 16px 0 0;
}

.najm-hoda-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.najm-hoda-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: #f8f9fa;
    direction: rtl;
}

.najm-hoda-message {
    display: flex;
    margin-bottom: 15px;
    animation: fadeInUp 0.3s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.najm-hoda-message.user {
    justify-content: flex-end;
}

.najm-hoda-message.user .najm-hoda-message-content {
    background: #007bff;
    color: white;
    border-radius: 12px 12px 0 12px;
    max-width: 70%;
}

.najm-hoda-message.assistant .najm-hoda-message-content {
    background: white;
    border-radius: 12px 12px 12px 0;
    max-width: 85%;
}

.najm-hoda-message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.najm-hoda-message.user .najm-hoda-message-avatar {
    order: 2;
    margin-left: 8px;
}

.najm-hoda-message.assistant .najm-hoda-message-avatar {
    margin-right: 8px;
}

.najm-hoda-message-content {
    padding: 10px 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.najm-hoda-message-content p:last-child {
    margin-bottom: 0;
}

.najm-hoda-typing {
    padding: 0 15px;
}

.najm-hoda-typing-indicator {
    background: white;
    padding: 10px 15px;
    border-radius: 12px;
    display: inline-flex;
    gap: 4px;
}

.najm-hoda-typing-indicator span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #999;
    animation: typing 1.4s infinite;
}

.najm-hoda-typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.najm-hoda-typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.7;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

.najm-hoda-footer {
    padding: 15px;
    background: white;
    border-top: 1px solid #dee2e6;
}

.najm-hoda-hints {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.najm-hoda-hint {
    font-size: 11px;
    padding: 4px 8px;
    background: #e9ecef;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.najm-hoda-hint:hover {
    background: #37c4b4;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .najm-hoda-chat-container {
        width: calc(100vw - 40px);
        height: calc(100vh - 120px);
    }
}

/* Scrollbar */
.najm-hoda-messages::-webkit-scrollbar {
    width: 6px;
}

.najm-hoda-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.najm-hoda-messages::-webkit-scrollbar-thumb {
    background: #37c4b4;
    border-radius: 3px;
}
</style>

<script>
// اسکریپت نجم‌هدا
(function() {
    'use strict';
    
    const NajmHoda = {
        conversationId: null,
        isTyping: false,
        
        init() {
            this.showWidget();
            this.ensureChatClosed(); // اطمینان از بسته بودن چت در ابتدا
            this.bindEvents();
            this.loadWelcome();
        },
        
        showWidget() {
            const widget = document.getElementById('najm-hoda-widget');
            if (widget) {
                widget.style.display = 'block';
            }
        },
        
        ensureChatClosed() {
            // اطمینان از بسته بودن چت در ابتدا
            const container = document.getElementById('najm-hoda-chat-container');
            if (container) {
                container.style.display = 'none';
                container.classList.add('d-none');
            }
        },
        
        bindEvents() {
            const toggleBtn = document.getElementById('najm-hoda-toggle');
            const closeBtn = document.getElementById('najm-hoda-close');
            const sendBtn = document.getElementById('najm-hoda-send');
            const input = document.getElementById('najm-hoda-input');
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => this.toggleChat());
            }
            
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.closeChat());
            }
            
            if (sendBtn) {
                sendBtn.addEventListener('click', () => this.sendMessage());
            }
            
            if (input) {
                input.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') this.sendMessage();
                });
            }
        },
        
        toggleChat() {
            const container = document.getElementById('najm-hoda-chat-container');
            if (!container) return;
            
            const isVisible = container.style.display !== 'none' && !container.classList.contains('d-none');
            
            if (isVisible) {
                // بستن چت
                this.closeChat();
            } else {
                // باز کردن چت
                container.style.display = 'flex';
                container.classList.remove('d-none');
                const input = document.getElementById('najm-hoda-input');
                if (input) {
                    setTimeout(() => input.focus(), 100);
                }
            }
        },
        
        closeChat() {
            const container = document.getElementById('najm-hoda-chat-container');
            if (container) {
                container.style.display = 'none';
                container.classList.add('d-none');
            }
        },
        
        async loadWelcome() {
            try {
                const response = await fetch('/api/najm-hoda/welcome');
                const data = await response.json();
                
                if (data.stats) {
                    console.log('نجم‌هدا آماده است:', data.stats);
                }
            } catch (error) {
                console.error('خطا در بارگذاری نجم‌هدا:', error);
            }
        },
        
        async sendMessage() {
            const input = document.getElementById('najm-hoda-input');
            const message = input.value.trim();
            
            if (!message || this.isTyping) return;
            
            // نمایش پیام کاربر
            this.addMessage(message, 'user', '👤');
            input.value = '';
            
            // نمایش typing indicator
            this.showTyping();
            
            try {
                const agent = document.getElementById('najm-hoda-agent').value;
                
                const response = await fetch('/api/najm-hoda/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Authorization': 'Bearer ' + (localStorage.getItem('api_token') || '')
                    },
                    body: JSON.stringify({
                        message: message,
                        agent: agent,
                        conversation_id: this.conversationId,
                    })
                });
                
                const data = await response.json();
                
                this.hideTyping();
                
                if (data.success) {
                    this.conversationId = data.conversation_id;
                    this.addMessage(data.message, 'assistant', data.agent_icon || '🤖');
                    
                    // نمایش پیشنهادات
                    if (data.suggestions && data.suggestions.length > 0) {
                        this.showSuggestions(data.suggestions);
                    }
                } else {
                    this.addMessage(data.message || 'خطایی رخ داد', 'assistant', '⚠️');
                }
                
            } catch (error) {
                this.hideTyping();
                this.addMessage('متأسفانه مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'assistant', '❌');
                console.error('خطا در ارسال پیام:', error);
            }
        },
        
        addMessage(content, role, icon) {
            const messagesDiv = document.getElementById('najm-hoda-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `najm-hoda-message ${role}`;
            
            messageDiv.innerHTML = `
                <div class="najm-hoda-message-avatar">${icon}</div>
                <div class="najm-hoda-message-content">${this.formatMessage(content)}</div>
            `;
            
            messagesDiv.appendChild(messageDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        },
        
        formatMessage(content) {
            // تبدیل Markdown ساده به HTML
            return content
                .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
                .replace(/\*([^*]+)\*/g, '<em>$1</em>')
                .replace(/\n/g, '<br>');
        },
        
        showTyping() {
            this.isTyping = true;
            document.getElementById('najm-hoda-typing').classList.remove('d-none');
            const messagesDiv = document.getElementById('najm-hoda-messages');
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        },
        
        hideTyping() {
            this.isTyping = false;
            document.getElementById('najm-hoda-typing').classList.add('d-none');
        },
        
        showSuggestions(suggestions) {
            const hintsDiv = document.getElementById('najm-hoda-hints');
            hintsDiv.innerHTML = '';
            
            suggestions.slice(0, 3).forEach(suggestion => {
                const hint = document.createElement('span');
                hint.className = 'najm-hoda-hint';
                hint.textContent = suggestion;
                hint.onclick = () => {
                    document.getElementById('najm-hoda-input').value = suggestion;
                    this.sendMessage();
                };
                hintsDiv.appendChild(hint);
            });
        }
    };
    
    // شروع خودکار
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => NajmHoda.init());
    } else {
        NajmHoda.init();
    }
    
    // در دسترس قرار دادن برای استفاده خارجی
    window.NajmHoda = NajmHoda;
})();
</script>
