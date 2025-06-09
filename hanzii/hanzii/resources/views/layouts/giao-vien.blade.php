    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* CSS cho hiển thị nội dung HTML */
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 0.375rem;
        }
        .prose figure {
            margin: 1.5em 0;
        }
        .prose figure figcaption {
            text-align: center;
            font-size: 0.875em;
            color: #6b7280;
        }
        .prose iframe {
            max-width: 100%;
            border-radius: 0.375rem;
            aspect-ratio: 16/9;
            width: 100%;
            height: auto;
        }
        .prose table {
            width: 100%;
            border-collapse: collapse;
        }
        .prose table th, .prose table td {
            border: 1px solid #e5e7eb;
            padding: 0.5em 0.75em;
        }
        .prose table th {
            background-color: #f9fafb;
        }
    </style>
    
    @stack('styles') 