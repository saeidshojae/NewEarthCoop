<template>
  <div class="chat-request">
    <button 
      v-if="!hasPendingRequest" 
      @click="sendRequest" 
      class="btn btn-primary"
    >
      درخواست چت
    </button>
    
    <div v-else-if="isReceiver" class="pending-request">
      <p>درخواست چت از {{ request.sender.name }}</p>
      <button @click="acceptRequest" class="btn btn-success">پذیرفتن</button>
      <button @click="rejectRequest" class="btn btn-danger">رد کردن</button>
    </div>
    
    <div v-else class="request-status">
      <p v-if="request.status === 'pending'">درخواست شما در حال انتظار است</p>
      <p v-else-if="request.status === 'accepted'">درخواست شما پذیرفته شد</p>
      <p v-else>درخواست شما رد شد</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    userId: {
      type: Number,
      required: true
    }
  },
  
  data() {
    return {
      request: null,
      hasPendingRequest: false,
      isReceiver: false
    };
  },
  
  async created() {
    await this.checkPendingRequests();
  },
  
  methods: {
    async checkPendingRequests() {
      try {
        const response = await axios.get('/api/chat-requests/pending');
        const requests = response.data;
        
        const existingRequest = requests.find(req => 
          (req.sender_id === this.userId && req.receiver_id === this.$auth.user.id) ||
          (req.sender_id === this.$auth.user.id && req.receiver_id === this.userId)
        );
        
        if (existingRequest) {
          this.request = existingRequest;
          this.hasPendingRequest = true;
          this.isReceiver = existingRequest.receiver_id === this.$auth.user.id;
        }
      } catch (error) {
        console.error('Error checking pending requests:', error);
      }
    },
    
    async sendRequest() {
      try {
        await axios.post(`/api/chat-requests/${this.userId}`);
        await this.checkPendingRequests();
      } catch (error) {
        console.error('Error sending chat request:', error);
      }
    },
    
    async acceptRequest() {
      try {
        await axios.post(`/api/chat-requests/${this.request.id}/accept`);
        await this.checkPendingRequests();
        this.$emit('chat-accepted');
      } catch (error) {
        console.error('Error accepting chat request:', error);
      }
    },
    
    async rejectRequest() {
      try {
        await axios.post(`/api/chat-requests/${this.request.id}/reject`);
        await this.checkPendingRequests();
      } catch (error) {
        console.error('Error rejecting chat request:', error);
      }
    }
  }
};
</script>

<style scoped>
.chat-request {
  margin: 10px 0;
}

.pending-request {
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn {
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  border: none;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-success {
  background-color: #28a745;
  color: white;
}

.btn-danger {
  background-color: #dc3545;
  color: white;
}
</style> 