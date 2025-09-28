# AI Prompt Generator

An intelligent Laravel-based web application that transforms vague user requests into professional, structured prompts optimized for various AI language models (ChatGPT, Claude, Gemini, Perplexity, etc.).

## 🚀 Features

- **Smart Analysis**: AI-powered analysis of user intent and requirements
- **Multi-Model Optimization**: Generates prompts tailored for different AI models
- **Interactive Refinement**: Asks clarifying questions to improve prompt quality
- **One-Click Integration**: Direct links to popular AI platforms with pre-filled prompts
- **Telegram Integration**: Share prompts via Telegram with custom formatting
- **Responsive Design**: Works seamlessly on desktop and mobile devices
- **User Authentication**: Laravel Breeze with social login support
- **Rate Limiting**: Built-in request limits for fair usage

## 🛠 Tech Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Alpine.js, Tailwind CSS, Vite
- **AI Integration**: Perplexity API for intelligent prompt generation
- **Authentication**: Laravel Breeze with Yandex OAuth
- **Database**: MySQL/PostgreSQL
- **Styling**: Tailwind CSS with custom components

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Perplexity API key

## ⚡ Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/ai-prompt-generator.git
   cd ai-prompt-generator
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure environment variables**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   PERPLEXITY_API_KEY=your_perplexity_api_key
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the application**
   ```bash
   php artisan serve
   ```

## 🎯 How It Works

1. **User Input**: Enter your request in natural language
2. **AI Analysis**: The system analyzes your intent and requirements
3. **Prompt Generation**: Creates a structured, professional prompt
4. **Clarification**: Optionally asks follow-up questions for better results
5. **Integration**: One-click sharing to your preferred AI platform

## 🔧 Configuration

### Rate Limits
- **Guest users**: 5 requests per day
- **Registered users**: Unlimited requests
- **Administrators**: Unlimited requests
- Configurable in `/config/ai.php`

### Supported AI Models
- ChatGPT
- Claude
- Gemini
- Perplexity
- DeepSeek
- Qwen
- Yandex Alice

## 📱 Usage

1. Visit the application in your browser
2. Enter your request in the text field
3. Optionally adjust advanced parameters
4. Click "Create Prompt"
5. Review the generated prompt and reasoning
6. Use one-click integration to send to your preferred AI platform

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🔒 Security

If you discover a security vulnerability, please send an email to [security@example.com](mailto:security@example.com). All security vulnerabilities will be promptly addressed.

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- Powered by [Perplexity AI](https://perplexity.ai)
- Styled with [Tailwind CSS](https://tailwindcss.com)
- Icons by [Heroicons](https://heroicons.com)

---

**Perfect for users who want to get better results from AI models without learning prompt engineering techniques.**
