<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Jobs - Job Management Dashboard</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Job Management Dashboard</h1>
            <p>Monitor current and failed jobs</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success') || session('error'))
            <div class="alert-container">
                @if(session('success'))
                    <div class="alert alert-success">
                        <span class="alert-icon">✓</span>
                        {{ session('success') }}
                        <button class="alert-close" onclick="this.parentElement.style.display='none'">&times;</button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error">
                        <span class="alert-icon">✗</span>
                        {{ session('error') }}
                        <button class="alert-close" onclick="this.parentElement.style.display='none'">&times;</button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Current Jobs Section -->
        <div class="jobs-section">
            <div class="section-header">
                <h2>Current Jobs</h2>
                <a href="#" class="clear-btn clear-jobs" onclick="promptPasswordAndRedirect('{{ route('jobs.deleteAll') }}')">Clear All Jobs</a>
            </div>
            <div class="jobs-grid">
                @forelse($jobs as $job)
                    <div class="job-card">
                        <div class="job-header">
                            <h3>Job #{{ $job->id }}</h3>
                            <span class="status-badge status-active">Active</span>
                        </div>
                        <div class="job-details">
                            <p><strong>Queue:</strong> {{ $job->queue ?? 'default' }}</p>
                            <p><strong>Payload:</strong> 
                                <button class="view-payload-btn" onclick="openPayloadModal('{{ $job->id }}', `{{ addslashes($job->payload) }}`)">Ver completo</button>
                            </p>
                            <p><strong>Attempts:</strong> {{ $job->attempts }}</p>
                            <p><strong>Created:</strong> {{ \Carbon\Carbon::parse($job->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>No current jobs found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Failed Jobs Section -->
        <div class="jobs-section">
            <div class="section-header">
                <h2>Failed Jobs</h2>
                <a href="#" class="clear-btn clear-failed-jobs" onclick="promptPasswordAndRedirect('{{ route('jobs.deleteAllFailed') }}')">Clear Failed Jobs</a>
            </div>
            <div class="jobs-grid">
                @forelse($failed_jobs as $failedJob)
                    <div class="job-card failed">
                        <div class="job-header">
                            <h3>Job #{{ $failedJob->id }}</h3>
                            <span class="status-badge status-failed">Failed</span>
                        </div>
                        <div class="job-details">
                            <p><strong>Queue:</strong> {{ $failedJob->queue ?? 'default' }}</p>
                            <p><strong>Connection:</strong> {{ $failedJob->connection }}</p>
                            <p><strong>Exception:</strong> 
                                <button class="view-exception-btn" onclick="openExceptionModal('{{ $failedJob->id }}', `{{ addslashes($failedJob->exception) }}`)">Ver completo</button>
                            </p>
                            <p><strong>Failed at:</strong> {{ \Carbon\Carbon::parse($failedJob->failed_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>No failed jobs found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal for payload -->
    <div id="payloadModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Payload completo - Job #<span id="modal-job-id"></span></h3>
                <span class="close" onclick="closePayloadModal()">&times;</span>
            </div>
            <div class="modal-body">
                <pre id="modal-payload-content"></pre>
            </div>
        </div>
    </div>

    <!-- Modal for exception -->
    <div id="exceptionModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Exception completa - Job #<span id="modal-exception-job-id"></span></h3>
                <span class="close" onclick="closeExceptionModal()">&times;</span>
            </div>
            <div class="modal-body">
                <pre id="modal-exception-content"></pre>
            </div>
        </div>
    </div>

    <script>
        function openPayloadModal(jobId, payload) {
            document.getElementById('modal-job-id').textContent = jobId;
            document.getElementById('modal-payload-content').textContent = payload;
            document.getElementById('payloadModal').style.display = 'block';
        }

        function closePayloadModal() {
            document.getElementById('payloadModal').style.display = 'none';
        }

        function openExceptionModal(jobId, exception) {
            document.getElementById('modal-exception-job-id').textContent = jobId;
            document.getElementById('modal-exception-content').textContent = exception;
            document.getElementById('exceptionModal').style.display = 'block';
        }

        function closeExceptionModal() {
            document.getElementById('exceptionModal').style.display = 'none';
        }

        function promptPasswordAndRedirect(url) {
            const password = prompt("Introduce la contraseña para eliminar los trabajos:");
            
            if (password === null) {
            return;
            }
            
            if (password === "PX!h3tERi4vUmW$") {
            const separator = url.includes('?') ? '&' : '?';
            window.location.href = url + separator + 'pass=' + encodeURIComponent(password);
            } else {
            alert("Contraseña incorrecta. Acceso denegado.");
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const payloadModal = document.getElementById('payloadModal');
            const exceptionModal = document.getElementById('exceptionModal');
            
            if (event.target === payloadModal) {
                payloadModal.style.display = 'none';
            }
            
            if (event.target === exceptionModal) {
                exceptionModal.style.display = 'none';
            }
        }
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 2.5rem;
        }

        .header p {
            margin: 0;
            opacity: 0.9;
        }

        .jobs-section {
            margin-bottom: 40px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .jobs-section h2 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
            margin: 0;
        }

        .clear-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: bold;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .clear-btn:hover {
            background-color: #c82333;
            text-decoration: none;
            color: white;
        }

        .jobs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
        }

        .job-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .job-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .job-card.failed {
            border-left: 5px solid #e74c3c;
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .job-header h3 {
            margin: 0;
            color: #333;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active {
            background-color: #27ae60;
            color: white;
        }

        .status-failed {
            background-color: #e74c3c;
            color: white;
        }

        .job-details p {
            margin: 8px 0;
            color: #666;
            line-height: 1.4;
        }

        .job-details strong {
            color: #333;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            color: #666;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }

        .payload-preview {
            font-family: monospace;
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
        }

        .view-payload-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
            margin-left: 8px;
        }

        .view-payload-btn:hover {
            background-color: #0056b3;
        }

        .exception-preview {
            font-family: monospace;
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
            color: #e74c3c;
        }

        .view-exception-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
            margin-left: 8px;
        }

        .view-exception-btn:hover {
            background-color: #c0392b;
        }

        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border: none;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .modal-header {
            padding: 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            color: #333;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .modal-body {
            padding: 20px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .modal-body pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #e9ecef;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.4;
            color: #333;
        }

        @media (max-width: 768px) {
            .jobs-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 2rem;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .section-header h2 {
                margin-bottom: 0;
            }
        }
    </style>
</body>
</html>
