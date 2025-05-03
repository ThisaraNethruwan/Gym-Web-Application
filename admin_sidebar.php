<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<aside class="fixed top-0 left-0 w-1/5 h-full bg-gray-900 text-gray-100 p-5 z-10">
        <nav class="flex flex-col space-y-2">
            <a href="admin.php#overview" class="flex items-center px-4 hover:text-gray-100 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span class="ml-3">Overview</span>
            </a>

            <a href="admin.php#messages" class="flex items-center px-4 hover:text-gray-100 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <span class="ml-3">Messages</span>
            </a>

            <a href="admin.php#user-management" class="flex items-center hover:text-gray-100 px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="ml-3">User Details</span>
            </a>

            <a href="admin.php#user-activities" class="flex items-center px-4 hover:text-gray-100 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="ml-3">User Activities</span>
            </a>

            <a href="upload_membership.php" class="flex items-center px-4 py-3 hover:text-gray-100 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="ml-3">Memberships</span>
            </a>

            <a href="admin.php#image-management" class="flex items-center hover:text-gray-100 px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="ml-3">Upload Image</span>
            </a>

            <a href="admin.php#gallery-management" class="flex items-center hover:text-gray-100 px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="ml-3">Gallery</span>
            </a>

            <a href="admin.php#blog-management" class="flex items-center px-4 hover:text-gray-100 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <span class="ml-3">Blog Management</span>
            </a>

            <a href="admin.php#blogs-display" class="flex items-center px-4 hover:text-gray-100 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="ml-3">Your Blog</span>
            </a>

            <a href="admin.php#settings" class="flex items-center px-4 py-3 hover:text-gray-100 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="ml-3">Settings</span>
            </a>

            <a href="handle_activity.php" class="flex items-center px-4 py-3 hover:text-gray-100 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="ml-3">Add Activities</span>
            </a>

            <a href="http://localhost/fitness/Employee-Attendance-Management-System/employee-attendance-management/index.php" class="flex items-center px-4 py-3 hover:text-gray-100 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group">
                <svg class="w-5 h-5 text-gray-400 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="ml-3">EMS System</span>
            </a>

            
            <a href="loggedout.php" class="flex items-center px-4 py-3 hover:text-gray-100 text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200 group mt-4">
    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
    </svg>
    <span class="ml-3">Logout</span>
</a>

        </nav>
    </aside>

    <script>
        feather.replace();
    </script>
         
   
</body>
</html>