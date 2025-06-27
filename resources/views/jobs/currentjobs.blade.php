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

        <!-- Current Jobs Section -->
        <div class="jobs-section">
            <h2>Current Jobs</h2>
            <div class="jobs-grid">
                @forelse($jobs as $job)
                    <div class="job-card">
                        <div class="job-header">
                            <h3>Job #{{ $job->id }}</h3>
                            <span class="status-badge status-active">Active</span>
                        </div>
                        <div class="job-details">
                            <p><strong>Queue:</strong> {{ $job->queue ?? 'default' }}</p>
                            <p><strong>Payload:</strong> {{ Str::limit($job->payload, 500) }}</p>
                            <p><strong>Attempts:</strong> {{ $job->attempts }}</p>
                            <p><strong>Created:</strong> {{ \Carbon\Carbon::parse($job->created_at)->format('Y-m-d H:i:s') }}</p>
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
            <h2>Failed Jobs</h2>
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
                            <p><strong>Exception:</strong> {{ Str::limit($failedJob->exception, 500) }}</p>
                            <p><strong>Failed at:</strong> {{ \Carbon\Carbon::parse($failedJob->failed_at)->format('Y-m-d H:i:s') }}</p>
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

        .jobs-section h2 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 20px;
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
        }
    </style>
</body>
</html>