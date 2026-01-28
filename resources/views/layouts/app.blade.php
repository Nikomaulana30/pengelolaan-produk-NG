<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Metinca</title>



    <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iCiAgICB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iCiAgICB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iCiAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiCiAgIGV4aWY6UGl4ZWxYRGltZW5zaW9uPSIzMyIKICAgZXhpZjpQaXhlbFlEaW1lbnNpb249IjM0IgogICBleGlmOkNvbG9yU3BhY2U9IjEiCiAgIHRpZmY6SW1hZ2VXaWR0aD0iMzMiCiAgIHRpZmY6SW1hZ2VMZW5ndGg9IjM0IgogICB0aWZmOlJlc29sdXRpb25Vbml0PSIyIgogICB0aWZmOlhSZXNvbHV0aW9uPSI5Ni4wIgogICB0aWZmOllSZXNvbHV0aW9uPSI5Ni4wIgogICBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIgogICBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiCiAgIHhtcDpNb2RpZnlEYXRlPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIgogICB4bXA6TWV0YWRhdGFEYXRlPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIj4KICAgPHhtcE1NOkhpc3Rvcnk+CiAgICA8cmRmOlNlcT4KICAgICA8cmRmOmxpCiAgICAgIHN0RXZ0OmFjdGlvbj0icHJvZHVjZWQiCiAgICAgIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFmZmluaXR5IERlc2lnbmVyIDEuMTAuMSIKICAgICAgc3RFdnQ6d2hlbj0iMjAyMi0wMy0zMVQxMDo1MDoyMyswMjowMCIvPgogICAgPC9yZGY6U2VxPgogICA8L3htcE1NOkhpc3Rvcnk+CiAgPC9yZGY6RGVzY3JpcHRpb24+CiA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgo8P3hwYWNrZXQgZW5kPSJyIj8+VjW7mAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png">

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    {{-- Disable dark mode by removing media query --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}" media="(prefers-color-scheme: dark)"> --}}
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <style>
        /* Dark mode responsive styles for readonly inputs and sections */
        [data-bs-theme="dark"] input[readonly],
        [data-bs-theme="dark"] input:disabled {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #e4e4e7 !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        /* Form controls in dark mode */
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select,
        [data-bs-theme="dark"] textarea.form-control {
            background-color: #2c3142 !important;
            color: #e4e4e7 !important;
            border-color: #495057 !important;
        }

        [data-bs-theme="dark"] .form-control::placeholder,
        [data-bs-theme="dark"] textarea.form-control::placeholder {
            color: #a1a1a1 !important;
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus,
        [data-bs-theme="dark"] textarea.form-control:focus {
            background-color: #353d4f !important;
            color: #e4e4e7 !important;
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        }

        /* Form labels in dark mode - strong override */
        [data-bs-theme="dark"] .form-label {
            color: #e4e4e7 !important;
        }

        [data-bs-theme="dark"] label {
            color: #e4e4e7 !important;
        }

        [data-bs-theme="dark"] .col-form-label {
            color: #e4e4e7 !important;
        }

        [data-bs-theme="dark"] small.form-text {
            color: #a1a1a1 !important;
        }

        [data-bs-theme="dark"] .form-text {
            color: #a1a1a1 !important;
        }

        /* Override any label with text-muted in dark mode */
        [data-bs-theme="dark"] label.text-muted {
            color: #a1a1a1 !important;
        }

        /* Text muted in dark mode */
        [data-bs-theme="dark"] .text-muted {
            color: #a1a1a1 !important;
        }

        /* Form section titles */
        [data-bs-theme="dark"] .form-section-title {
            color: #e4e4e7 !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-bottom-color: #667eea !important;
        }

        /* Form group boxes */
        [data-bs-theme="dark"] .form-group-box {
            background-color: #2c3142 !important;
            border-color: #495057 !important;
            color: #e4e4e7 !important;
        }

        /* Ensure small text inside form-group-box is readable */
        [data-bs-theme="dark"] .form-group-box small {
            color: #a1a1a1 !important;
        }

        /* Table styling in dark mode */
        [data-bs-theme="dark"] .table {
            color: #e4e4e7 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        [data-bs-theme="dark"] .table-light,
        [data-bs-theme="dark"] .table thead {
            background-color: #2c3142 !important;
            color: #e4e4e7 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        [data-bs-theme="dark"] .table thead th {
            color: #e4e4e7 !important;
            background-color: #2c3142 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        [data-bs-theme="dark"] .table tbody tr {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        [data-bs-theme="dark"] .table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }

        [data-bs-theme="dark"] .table td,
        [data-bs-theme="dark"] .table th {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Card styling in dark mode */
        [data-bs-theme="dark"] .card {
            background-color: #1e2230 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #e4e4e7 !important;
        }

        [data-bs-theme="dark"] .card-header {
            background-color: #2c3142 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #e4e4e7 !important;
        }

        [data-bs-theme="dark"] .card-body {
            background-color: #1e2230 !important;
            color: #e4e4e7 !important;
        }

        [data-bs-theme="dark"] .card-title {
            color: #e4e4e7 !important;
        }

        /* List group styling in dark mode */
        [data-bs-theme="dark"] .list-group {
            background-color: #1e2230 !important;
        }

        [data-bs-theme="dark"] .list-group-item {
            background-color: #2c3142 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #e4e4e7 !important;
        }

        [data-bs-theme="dark"] .list-group-item-action:hover,
        [data-bs-theme="dark"] .list-group-item-action:focus {
            background-color: #353d4f !important;
            color: #e4e4e7 !important;
        }

        /* Form check (radio/checkbox) alignment fix */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form check universal styling - both light and dark */
        .form-check {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            margin-bottom: 0 !important;
        }

        .form-check-input {
            flex-shrink: 0;
            margin-top: 0 !important;
            margin-right: 0 !important;
            margin-bottom: 0 !important;
            margin-left: 0 !important;
            width: 1rem;
            height: 1rem;
        }

        .form-check-label {
            margin-bottom: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
            cursor: pointer;
            line-height: 1;
        }

        /* Form switch (toggle) styling - keep original Bootstrap behavior */
        .form-switch {
            padding-left: 2.5em;
            display: flex;
            align-items: center;
        }

        .form-switch .form-check-input {
            width: 2em;
            height: 1em;
            margin-top: 0 !important;
            margin-left: -2.5em !important;
            margin-right: 0 !important;
            margin-bottom: 0 !important;
            border-radius: 0.5em;
        }

        .form-switch .form-check-label {
            margin-left: 0;
            margin-bottom: 0;
            margin-top: 0;
        }

        /* Theme toggle alignment */
        .theme-toggle {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            margin-top: 1rem !important;
            margin-bottom: 0 !important;
            height: 40px;
        }

        .theme-toggle svg {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            min-height: 20px;
        }

        .theme-toggle .form-switch {
            display: flex;
            align-items: center;
            margin: 0 !important;
            padding-left: 2.5em !important;
        }

        .theme-toggle .form-switch .form-check-input {
            width: 2.5em !important;
            height: 1.25em !important;
            cursor: pointer;
            background-color: #6c757d;
            border: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e") !important;
            background-position: left center;
            background-size: contain;
            transition: background-position 0.15s ease-in-out;
        }

        .theme-toggle .form-switch .form-check-input:checked {
            background-color: #667eea !important;
            background-position: right center !important;
            border-color: #667eea !important;
        }

        .theme-toggle .form-switch .form-check-input:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        }

        /* Dark mode form switch */
        [data-bs-theme="dark"] .form-switch .form-check-input {
            background-color: #495057 !important;
        }

        [data-bs-theme="dark"] .form-switch .form-check-input:checked {
            background-color: #667eea !important;
            border-color: #667eea !important;
        }

        /* Light mode form switch */
        [data-bs-theme="light"] .form-switch .form-check-input {
            background-color: #dee2e6 !important;
            border-color: #dee2e6 !important;
        }

        [data-bs-theme="light"] .form-switch .form-check-input:checked {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        /* Dark mode form check styling */
        [data-bs-theme="dark"] .form-check-input {
            background-color: #2c3142 !important;
            border-color: #495057 !important;
            margin-top: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
            margin-bottom: 0 !important;
        }

        [data-bs-theme="dark"] .form-check-input:checked {
            background-color: #667eea !important;
            border-color: #667eea !important;
        }

        [data-bs-theme="dark"] .form-check-input:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
        }

        [data-bs-theme="dark"] .form-check-label {
            color: #e4e4e7 !important;
            margin-bottom: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        [data-bs-theme="dark"] .form-check {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }

        /* Light mode form check styling */
        [data-bs-theme="light"] .form-check-input {
            background-color: #fff !important;
            border-color: #dee2e6 !important;
            margin-top: 0 !important;
            margin-right: 0 !important;
            margin-bottom: 0 !important;
            margin-left: 0 !important;
        }

        [data-bs-theme="light"] .form-check-label {
            color: #212529 !important;
            margin-bottom: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        /* Override Bootstrap default form-check margin */
        [data-bs-theme="light"] .form-check {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }

        /* Form check (radio/checkbox) in dark mode */
        [data-bs-theme="dark"] .form-check-input {
            background-color: #2c3142 !important;
            border-color: #495057 !important;
        }

        [data-bs-theme="dark"] .form-check-input:checked {
            background-color: #667eea !important;
            border-color: #667eea !important;
        }

        [data-bs-theme="dark"] .form-check-label {
            color: #d4d4d8 !important;
        }

        [data-bs-theme="dark"] .section-header {
            background-color: #3a3f4b !important;
            color: #e4e4e7 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        [data-bs-theme="dark"] [style*="background-color: #f8f9fa"],
        [data-bs-theme="dark"] [style*="background-color: #f0f0f0"],
        [data-bs-theme="dark"] [style*="background-color: #e9ecef"] {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #e4e4e7 !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        [data-bs-theme="dark"] [style*="border: 2px solid #ddd"],
        [data-bs-theme="dark"] [style*="border: 1px solid #e3e6f0"] {
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        [data-bs-theme="dark"] [style*="color: #333"],
        [data-bs-theme="dark"] [style*="color: #333;"],
        [data-bs-theme="dark"] [style*="color: #666"],
        [data-bs-theme="dark"] [style*="color: #6c757d"],
        [data-bs-theme="dark"] [style*="color: #6c757d;"],
        [data-bs-theme="dark"] strong[style*="color"],
        [data-bs-theme="dark"] span[style*="color"],
        [data-bs-theme="dark"] small[style*="color"],
        [data-bs-theme="dark"] p[style*="color"],
        [data-bs-theme="dark"] label[style*="color"] {
            color: #a1a1a1 !important;
        }

        [data-bs-theme="dark"] strong[style*="color: #333"],
        [data-bs-theme="dark"] span[style*="color: #333"] {
            color: #e4e4e7 !important;
        }

        [data-bs-theme="dark"] .alert-info,
        [data-bs-theme="dark"] [style*="background-color: #e7f3ff"] {
            background-color: rgba(23, 162, 184, 0.15) !important;
            color: #5dd4f3 !important;
            border-color: rgba(93, 212, 243, 0.3) !important;
        }

        [data-bs-theme="dark"] .alert-secondary,
        [data-bs-theme="dark"] [style*="background-color: #f0f0f0"] {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #a1a1a1 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Light mode fallback */
        [data-bs-theme="light"] input[readonly],
        [data-bs-theme="light"] input:disabled {
            background-color: #f0f0f0 !important;
            color: #333 !important;
            border-color: #ddd !important;
        }

        [data-bs-theme="light"] .section-header {
            background-color: #E7E6E6 !important;
            color: #333 !important;
        }

        [data-bs-theme="light"] [style*="color: #6c757d"],
        [data-bs-theme="light"] small[style*="color: #6c757d"] {
            color: #6c757d !important;
        }

        /* Dark mode sidebar styling */
        [data-bs-theme="dark"] #sidebar {
            background-color: #1e1e2d !important;
        }

        [data-bs-theme="dark"] #sidebar .sidebar-wrapper {
            background-color: #1e1e2d !important;
        }

        [data-bs-theme="dark"] .sidebar-title {
            color: #a1a1a1 !important;
        }

        [data-bs-theme="dark"] .sidebar-link {
            color: #e4e4e7 !important;
        }

        [data-bs-theme="dark"] .sidebar-item.active .sidebar-link,
        [data-bs-theme="dark"] .sidebar-item.active > .sidebar-link {
            background-color: rgba(102, 126, 234, 0.2) !important;
            color: #667eea !important;
        }

        [data-bs-theme="dark"] .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        [data-bs-theme="dark"] .submenu .submenu-item a {
            color: #a1a1a1 !important;
        }

        [data-bs-theme="dark"] .submenu .submenu-item.active > a {
            color: #667eea !important;
            font-weight: 600;
        }

        /* Dark mode for user profile section in sidebar */
        [data-bs-theme="dark"] .sidebar-item .sidebar-link[style*="background: linear-gradient"] {
            background: linear-gradient(135deg, #2c3142 0%, #1e1e2d 100%) !important;
        }

        [data-bs-theme="dark"] .sidebar-item .sidebar-link h6 {
            color: #e4e4e7 !important;
        }
    </style>
    @stack('styles')
</head>

<body data-bs-theme="{{ auth()->check() ? (auth()->user()->theme ?? 'light') : 'light' }}">
    <div id="app">
        <div id="sidebar" data-bs-theme="{{ auth()->check() ? (auth()->user()->theme ?? 'light') : 'light' }}">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="index.html"><img src="{{ asset('assets/compiled/jpg/logo metinca.jpg') }}" alt="Logo"
                                    style="height: 70px; width: auto; margin-right: 12px;">
                                <span style="font-size: 15px; font-weight: 600; color: #333;">
                                    METINCA PRIMA INDUSTRIAL WORKS
                                </span>
                            </a>
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20"
                                height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                        opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark"
                                    style="cursor: pointer">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true" role="img" class="iconify iconify--mdi" width="20"
                                height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                </path>
                            </svg>
                        </div>
                        <div class="sidebar-toggler  x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                    class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} ">
                            <a href="{{ route('dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-title">Data Management</li>

                        <!-- ============ DATA MASTER MENU (Admin Only) ============ -->
                        @if(auth()->user()->canAccess('master-data'))
                        <li class="sidebar-item has-sub {{ request()->routeIs('master-produk.*', 'master-defect.*', 'master-lokasi.*', 'master-vendor.*', 'master-disposisi.*', 'master-approval.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-database-fill"></i>
                                <span>DATA MASTER</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->routeIs('master-produk.*') ? 'active' : '' }}">
                                    <a href="{{ route('master-produk.index') }}">
                                        <i class="bi bi-box2 me-2"></i>Master Produk
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('master-defect.*') ? 'active' : '' }}">
                                    <a href="{{ route('master-defect.index') }}">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Master Defect
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('master-lokasi.*') ? 'active' : '' }}">
                                    <a href="{{ route('master-lokasi.index') }}">
                                        <i class="bi bi-geo-alt me-2"></i>Master Lokasi Gudang
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('master-vendor.*') ? 'active' : '' }}">
                                    <a href="{{ route('master-vendor.index') }}">
                                        <i class="bi bi-building me-2"></i>Master Vendor/Supplier
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('master-disposisi.*') ? 'active' : '' }}">
                                    <a href="{{ route('master-disposisi.index') }}">
                                        <i class="bi bi-arrow-left-right me-2"></i>Master Disposisi
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('master-approval.*') ? 'active' : '' }}">
                                    <a href="{{ route('master-approval.index') }}">
                                        <i class="bi bi-person-check me-2"></i>Master Approval Authority
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- ============ PPIC MENU ============ -->
                        @if(auth()->user()->canAccess('ppic'))
                        <li class="sidebar-item has-sub {{ request()->routeIs('rca-analysis.*', 'ppic.approval.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-diagram-3-fill"></i>
                                <span>PPIC</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->routeIs('rca-analysis.*') ? 'active' : '' }}">
                                    <a href="{{ route('rca-analysis.index') }}">
                                        <i class="bi bi-diagram-3 me-2"></i>RCA Analysis
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('ppic.approval.*') ? 'active' : '' }}">
                                    <a href="{{ route('ppic.approval.index') }}">
                                        <i class="bi bi-file-earmark-check me-2"></i>Approval (Finance)
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- ============ WAREHOUSE MENU ============ -->
                        @if(auth()->user()->canAccess('warehouse'))
                        <li class="sidebar-item has-sub {{ request()->routeIs('penerimaan-barang.*', 'retur-barang.*', 'penyimpanan-ng.*', 'scrap-disposal.*', 'warehouse.approval.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-shop-window"></i>
                                <span>WAREHOUSE</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->routeIs('penerimaan-barang.*') ? 'active' : '' }}">
                                    <a href="{{ route('penerimaan-barang.index') }}">
                                        <i class="bi bi-box-arrow-in-down me-2"></i>Penerimaan Barang
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('retur-barang.*') ? 'active' : '' }}">
                                    <a href="{{ route('retur-barang.index') }}">
                                        <i class="bi bi-arrow-left-square me-2"></i>Retur Barang
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('penyimpanan-ng.*') ? 'active' : '' }}">
                                    <a href="{{ route('penyimpanan-ng.index') }}">
                                        <i class="bi bi-archive me-2"></i>Penyimpanan NG
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('disposisi-assignment.*') ? 'active' : '' }}">
                                    <a href="{{ route('disposisi-assignment.index') }}">
                                        <i class="bi bi-diagram-3 me-2"></i>Disposisi Assignment
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('scrap-disposal.*') ? 'active' : '' }}">
                                    <a href="{{ route('scrap-disposal.index') }}">
                                        <i class="bi bi-trash me-2"></i>Scrap/Disposal
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('warehouse.approval.*') ? 'active' : '' }}">
                                    <a href="{{ route('warehouse.approval.index') }}">
                                        <i class="bi bi-file-earmark-check me-2"></i>Approval
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- ============ QUALITY MENU ============ -->
                        @if(auth()->user()->canAccess('quality'))
                        <li class="sidebar-item has-sub {{ request()->routeIs('inspeksi-qc.*', 'quality.approval.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-shield-check"></i>
                                <span>QUALITY</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->routeIs('inspeksi-qc.*') ? 'active' : '' }}">
                                    <a href="{{ route('inspeksi-qc.index') }}">
                                        <i class="bi bi-search me-2"></i>Inspeksi/QC
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('quality.approval.*') ? 'active' : '' }}">
                                    <a href="{{ route('quality.approval.index') }}">
                                        <i class="bi bi-check-circle me-2"></i>Approval
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- ============ REPORTS MENU ============ -->
                        @if(auth()->user()->canAccess('reports'))
                        <li class="sidebar-item has-sub {{ request()->routeIs('reports.*', 'vendor-scorecard.*', 'laporan-recap.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-file-earmark-bar-graph"></i>
                                <span>REPORTS</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->routeIs('laporan-recap.*') ? 'active' : '' }}">
                                    <a href="{{ route('laporan-recap.index') }}">
                                        <i class="bi bi-file-earmark-text me-2"></i>Laporan Recap PPIC
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('reports.return-analysis') ? 'active' : '' }}">
                                    <a href="{{ route('reports.return-analysis') }}">
                                        <i class="bi bi-graph-up-arrow me-2"></i>Return Analysis
                                    </a>
                                </li>
                                <li class="submenu-item {{ request()->routeIs('vendor-scorecard.*') ? 'active' : '' }}">
                                    <a href="{{ route('vendor-scorecard.index') }}">
                                        <i class="bi bi-graph-up me-2"></i>Vendor Scorecard
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- ============ USER MANAGEMENT MENU (Admin Only) ============ -->
                        @if(auth()->user()->canAccess('user-management'))
                        <li class="sidebar-item has-sub {{ request()->routeIs('user.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>USER MANAGEMENT</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->routeIs('user.index') ? 'active' : '' }}">
                                    <a href="{{ route('user.index') }}">
                                        <i class="bi bi-person-lines-fill me-2"></i>Manajemen User
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- ============ LOGOUT SECTION ============ -->
                        <li class="sidebar-title mt-3">Account</li>
                        <li class="sidebar-item">
                            <div class="sidebar-link d-flex align-items-center p-3 mx-2 mb-2" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px;">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}?t={{ time() }}" 
                                         alt="{{ auth()->user()->name }}"
                                         class="me-3 rounded-circle"
                                         style="width: 44px; height: 44px; object-fit: cover;">
                                @else
                                    <div class="me-3 bg-{{ auth()->user()->getRoleBadgeColor() }}" style="width: 44px; height: 44px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                        <span class="text-white" style="font-weight: 600; font-size: 15px; margin: 0; padding: 0; display: block; text-align: center;">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-dark" style="font-size: 13px;">{{ auth()->user()->name }}</h6>
                                    <span class="badge bg-{{ auth()->user()->getRoleBadgeColor() }}" style="font-size: 10px;">
                                        {{ auth()->user()->getRoleDisplayName() }}
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li class="sidebar-item">
                            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="sidebar-link btn btn-link text-danger w-100 text-start" style="text-decoration: none;">
                                    <i class="bi bi-box-arrow-left"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <div id="main" data-bs-theme="{{ auth()->check() ? (auth()->user()->theme ?? 'light') : 'light' }}">
            <header>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">
                                <li class="nav-item dropdown me-1">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-envelope bi-sub fs-4"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <h6 class="dropdown-header">Mail</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="#">No new mail</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown me-3">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                                        data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        <i class="bi bi-bell bi-sub fs-4"></i>
                                        @php
                                            // Cek jika ada pending approvals atau notifikasi
                                            $hasNotifications = false;
                                            $notificationCount = 0;
                                            
                                            // You can add logic here to check for pending approvals, etc
                                            // For now, just show badge if approval_notifications is enabled
                                        @endphp
                                        {{-- Badge will show if there are actual notifications in the future --}}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                                        aria-labelledby="dropdownMenuButton">
                                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">Notifications</h6>
                                            <a href="{{ route('settings.index') }}" class="text-muted" style="font-size: 0.85rem;">
                                                <i class="bi bi-gear"></i>
                                            </a>
                                        </li>
                                        {{-- Placeholder: Notification system will be implemented in the future --}}
                                        @if(!auth()->user()->email_notifications && !auth()->user()->approval_notifications && !auth()->user()->activity_notifications)
                                            <li class="dropdown-item text-center py-3">
                                                <small class="text-muted">Notifikasi dimatikan</small>
                                            </li>
                                        @else
                                            {{-- Future: Display actual notifications from database --}}
                                            <li class="dropdown-item text-center py-3">
                                                <small class="text-muted">Tidak ada notifikasi</small>
                                            </li>
                                            {{--
                                            @if($notifications->count() > 0)
                                                @foreach($notifications as $notif)
                                                    <li class="dropdown-item notification-item">
                                                        <a class="d-flex align-items-center" href="{{ $notif->action_url ?? '#' }}">
                                                            <div class="notification-icon bg-{{ $notif->badge_color }}">
                                                                <i class="bi {{ $notif->icon ?? 'bi-bell' }}"></i>
                                                            </div>
                                                            <div class="notification-text ms-4">
                                                                <p class="notification-title font-bold">
                                                                    {{ $notif->title }}
                                                                </p>
                                                                <p class="notification-subtitle font-thin text-sm">
                                                                    {{ Str::limit($notif->message, 50) }}
                                                                </p>
                                                                <small class="text-muted">
                                                                    {{ $notif->created_at->diffForHumans() }}
                                                                </small>
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="dropdown-item text-center py-3">
                                                    <small class="text-muted">Tidak ada notifikasi</small>
                                                </li>
                                            @endif
                                            --}}
                                        @endif
                                        <li><hr class="dropdown-divider" /></li>
                                        <li class="px-3 py-2">
                                            <small class="text-muted d-block mb-2"><strong>Preferensi</strong></small>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input notification-toggle" type="checkbox" 
                                                       id="email_notif_bell" data-type="email_notifications"
                                                       {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_notif_bell">
                                                    <small>Email</small>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input notification-toggle" type="checkbox" 
                                                       id="approval_notif_bell" data-type="approval_notifications"
                                                       {{ auth()->user()->approval_notifications ? 'checked' : '' }}>
                                                <label class="form-check-label" for="approval_notif_bell">
                                                    <small>Approval</small>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input notification-toggle" type="checkbox" 
                                                       id="activity_notif_bell" data-type="activity_notifications"
                                                       {{ auth()->user()->activity_notifications ? 'checked' : '' }}>
                                                <label class="form-check-label" for="activity_notif_bell">
                                                    <small>Activity</small>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <p class="text-center py-2 mb-0">
                                                <a href="{{ route('settings.index') }}">Pengaturan Lengkap</a>
                                            </p>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">{{ auth()->user()->name }}</h6>
                                            <p class="mb-0 text-sm text-gray-600">
                                                <span class="badge bg-{{ auth()->user()->getRoleBadgeColor() }}">
                                                    {{ auth()->user()->getRoleDisplayName() }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                @if(auth()->user()->avatar)
                                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}?t={{ time() }}" 
                                                         alt="{{ auth()->user()->name }}" />
                                                @else
                                                    <div class="avatar-content bg-{{ auth()->user()->getRoleBadgeColor() }}" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                                        <span class="text-white" style="font-weight: 600; font-size: 1.2rem;">
                                                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                                    style="min-width: 11rem">
                                    <li>
                                        <h6 class="dropdown-header">Hello, {{ auth()->user()->name }}!</h6>
                                    </li>
                                    <li class="px-3 py-1">
                                        <span class="badge bg-{{ auth()->user()->getRoleBadgeColor() }} w-100">
                                            {{ auth()->user()->getRoleDisplayName() }}
                                        </span>
                                    </li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}"><i
                                                class="icon-mid bi bi-person me-2"></i> My Profile</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('settings.index') }}"><i
                                                class="icon-mid bi bi-gear me-2"></i> Settings</a>
                                    </li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li class="px-3 py-2">
                                        <small class="text-muted d-block mb-2"><strong>Notifikasi</strong></small>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input notification-toggle" type="checkbox" 
                                                   id="email_notif" data-type="email_notifications"
                                                   {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                                            <label class="form-check-label" for="email_notif">
                                                <small>Email</small>
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input notification-toggle" type="checkbox" 
                                                   id="approval_notif" data-type="approval_notifications"
                                                   {{ auth()->user()->approval_notifications ? 'checked' : '' }}>
                                            <label class="form-check-label" for="approval_notif">
                                                <small>Approval</small>
                                            </label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input notification-toggle" type="checkbox" 
                                                   id="activity_notif" data-type="activity_notifications"
                                                   {{ auth()->user()->activity_notifications ? 'checked' : '' }}>
                                            <label class="form-check-label" for="activity_notif">
                                                <small>Activity</small>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <form id="formLogout">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <div class="main-content">
                @yield('content')
            </div>


            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2025 &copy; Sistem Informasi Universitas Darma Persada</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted with <span class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span>
                            by <a href="si.unsada.ac.id">NIKO MAULANA</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
     <!-- App JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>

        // Logout handler function
        function handleLogout(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    App.ajax('{{ route('logout') }}', 'POST', new FormData(this))
                        .then(response => {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Anda telah logout.',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '{{ route('login') }}';
                            });
                        })
                        .catch(error => {
                            console.error('Logout error:', error);
                            App.error('Gagal Logout', 'Terjadi kesalahan saat logout.');
                        });
                }
            });
        }

        // Profile dropdown logout
        document.getElementById('formLogout').addEventListener('submit', handleLogout);

        // Sidebar logout
        document.getElementById('sidebar-logout-form').addEventListener('submit', handleLogout);

        // Notification preference toggles
        document.querySelectorAll('.notification-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const type = this.dataset.type;
                const value = this.checked ? 1 : 0;
                
                fetch('{{ route('settings.update-notifications') }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        [type]: value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Sync all toggles with same data-type
                    document.querySelectorAll(`.notification-toggle[data-type="${type}"]`).forEach(t => {
                        t.checked = value ? true : false;
                    });
                    
                    // Update notification badge
                    updateNotificationBadge();
                    
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Preferensi notifikasi diperbarui.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal memperbarui preferensi notifikasi.',
                        icon: 'error'
                    });
                    // Revert toggle
                    this.checked = !this.checked;
                });
            });
        });

        // Update notification badge
        function updateNotificationBadge() {
            const emailNotif = document.getElementById('email_notif_bell') ? document.getElementById('email_notif_bell').checked : false;
            const approvalNotif = document.getElementById('approval_notif_bell') ? document.getElementById('approval_notif_bell').checked : false;
            const activityNotif = document.getElementById('activity_notif_bell') ? document.getElementById('activity_notif_bell').checked : false;
            
            const bellIcon = document.querySelector('.bi-bell').parentElement;
            const badge = bellIcon.querySelector('.badge');
            
            if (emailNotif || approvalNotif || activityNotif) {
                // At least one notification is enabled
                if (!badge) {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'badge badge-notification bg-danger';
                    newBadge.textContent = approvalNotif ? '!' : '';
                    bellIcon.appendChild(newBadge);
                } else {
                    badge.textContent = approvalNotif ? '!' : '';
                    badge.style.display = 'inline-block';
                }
            } else {
                // All notifications disabled
                if (badge) {
                    badge.style.display = 'none';
                }
            }
        }

    </script>
    @stack('scripts')
    <!-- Need: Apexcharts -->

</body>

</html>

