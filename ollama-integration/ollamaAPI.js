const axios = require('axios');

// Địa chỉ API của Ollama
const response = await axios.post('http://localhost:11434/v1/health', {
    prompt: "Hello, can you help me with my code?"
  });
  ; // Đảm bảo cổng và URL đúng

// Hàm gửi yêu cầu đến Ollama API
async function sendToOllama(prompt) {
    try {
        const response = await axios.post(url, {
            prompt: prompt // Đây là câu hỏi bạn muốn gửi cho Ollama
        });

        console.log("Response from Ollama:", response.data);
    } catch (error) {
        console.error("Error calling Ollama API:", error);
    }
}

// Gọi hàm với một câu hỏi ví dụ
sendToOllama("Hello, can you help me with my code?");
