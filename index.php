<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FrameKlip - Jasa Editing Video Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .navy {
            background-color: #0f172a; /* Lebih gelap dari #1e3a8a */
        }
        
        .orange {
            background-color: #f97316;
        }
        
        .text-navy {
            color: #1e3a8a;
        }
        
        .text-orange {
            color: #f97316;
        }
        
        .btn-orange {
            background-color: #f97316;
            color: white;
            transition: all 0.3s;
        }
        
        .btn-orange:hover {
            background-color: #ea580c;
            transform: translateY(-2px);
        }
        
        .btn-navy {
            background-color: #1e3a8a;
            color: white;
            transition: all 0.3s;
        }
        
        .btn-navy:hover {
            background-color: #1e40af;
            transform: translateY(-2px);
        }
        
        .hero-bg {
            background: linear-gradient(rgba(15, 23, 42, 0.92), rgba(15, 23, 42, 0.92)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%230f172a" width="1200" height="600"/><g fill="%23f97316" opacity="0.1"><circle cx="200" cy="150" r="100"/><circle cx="800" cy="400" r="150"/><circle cx="1000" cy="200" r="80"/></g><text x="600" y="300" font-family="Arial" font-size="120" fill="%23ffffff" text-anchor="middle" opacity="0.1">EDIT</text></svg>');
            background-size: cover;
            background-position: center;
        }
        
        .card-service {
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .card-service:hover {
            transform: translateY(-10px);
            border-color: #f97316;
            box-shadow: 0 20px 40px rgba(249, 115, 22, 0.2);
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6);
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideIn 0.3s;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .loading {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #f97316;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="navy fixed w-full z-50 shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex items-center space-x-3">
                        <img src="logo.png" alt="FrameKlip Logo" class="h-14 w-14 object-cover rounded-full" style="background: transparent; padding: 2px;">
                        <h1 class="text-3xl font-bold text-white">Frame<span class="text-orange">Klip</span></h1>
                    </div>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="text-white font-semibold transition-all duration-300" onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='white'">Home</a>
                    <a href="#layanan" class="text-white font-semibold transition-all duration-300" onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='white'">Services</a>
                    <a href="#about" class="text-white font-semibold transition-all duration-300" onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='white'">About</a>
                    <a href="#contact" class="text-white font-semibold transition-all duration-300" onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='white'">Contact</a>
                </div>
                <button id="mobileMenuBtn" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-2">
                <a href="#home" class="block text-white hover:text-orange transition py-2">Home</a>
                <a href="#layanan" class="block text-white hover:text-orange transition py-2">Services</a>
                <a href="#about" class="block text-white hover:text-orange transition py-2">About</a>
                <a href="#contact" class="block text-white hover:text-orange transition py-2">Contact</a>
            </div>
        </div>
    </nav>
