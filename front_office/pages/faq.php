<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Section</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            margin: 0;
        }
        
        .faq-container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            margin-bottom: 50px;
            gap: 30px;
            align-items: stretch;
            flex-direction: column;
        }
        
        .faq-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .faq-right {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .faq-title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #0C1BA3;
            margin-bottom: 30px;
        }
        
        .faq-item {
            background-color: #0C1BA3;
            color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .faq-item.active {
            background-color: #02FA72;
            color: #0C1BA3;
        }
        
        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: 600;
        }
        
        .faq-answer {
            font-size: 16px;
            font-weight: 400;
            margin-top: 15px;
            display: none;
            color: #0C1BA3;
        }
        
        .faq-item.active .faq-answer {
            display: block;
        }
        
        .contact-box {
            background-color: #0C1BA3;
            border-radius: 10px;
            padding: 20px;*
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .contact-icon {
            font-size: 30px;
            color: white;
            margin-bottom: 10px;
        }
        
        .contact-title {
            font-size: 16px;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }
        
        .contact-text {
            font-size: 14px;
            font-weight: 400;
            color: white;
            margin-bottom: 15px;
        }
        
        .contact-button {
            background-color: #02FA72;
            color: #0C1BA3;
            border: none;
            border-radius: 5px;
            padding: 10px 20px; 
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: auto;
        }
        
        .contact-button:hover {
            opacity: 0.9;
        }
        
        .call-box {
            background-color: #02FA72;
            border-radius: 10px;
            padding: 20px; 
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .call-title {
            font-size: 20px; 
            font-weight: 700;
            color: #0C1BA3;
            margin-bottom: 10px;
        }
        
        .call-number {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 20px; 
            font-weight: 700;
            color: #0C1BA3;
        }
        
        .phone-icon {
            font-size: 16px; 
        }

        @media (min-width: 992px) {
            .faq-container {
                flex-direction: row; 
            }
            
            .faq-right {
                width: 350px; 
            }
            
            .faq-title {
                font-size: 36px;
                margin-bottom: 40px;
            }
            
            .faq-item {
                padding: 20px;
            }
            
            .faq-question {
                font-size: 24px;
            }
            
            .faq-answer {
                font-size: 20px;
            }
            
            .contact-box {
                padding: 30px;
            }
            
            .contact-icon {
                font-size: 40px;
            }
            
            .contact-button {
                padding: 12px 25px;
                font-size: 16px;
            }
            
            .call-box {
                padding: 30px;
            }
            
            .call-title {
                font-size: 24px;
            }
            
            .call-number {
                font-size: 24px;
            }
            
            .phone-icon {
                font-size: 20px;
            }
        }

        @media (max-width: 991px) and (min-width: 768px) {
            .faq-right {
                flex-direction: row; 
            }
            
            .contact-box,
            .call-box {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <h1 class="faq-title">Got Questions?</h1>
    
    <div class="faq-container">
        <div class="faq-left">
            <div class="faq-item">
                <div class="faq-question">
                    <span>What is Masar?</span>
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Masar is a platform connecting innovative startups with public institutions to solve road safety challenges in Tunisia.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Who can join Masar?</span>
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Startups offering road safety solutions and public institutions seeking innovation.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>How does Masar work?</span>
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Institutions post needs or challenges or events; startups propose solutions or showcase their projects.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>How are startups selected for collaboration?</span>
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Institutions review proposals and directly contact startups they're interested in.
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I get started?</span>
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Simple. Create an account, get approved, and either post your need or pitch your project.
                </div>
            </div>
        </div>
        
        <div class="faq-right">
            <div class="contact-box">
                <div class="contact-icon">
                    <i class="far fa-question-circle"></i>
                </div>
                <h3 class="contact-title">You have different questions?</h3>
                <p class="contact-text">Masar's team will answer all your questions.<br>We ensure a quick response.</p>
                
                <button class="contact-button" onclick="window.location.href='mailto:masar.platform.tn@gmail.com?subject=Contact%20Request&body=Hello%2C%20I%20would%20like%20to%20get%20in%20touch%20with%20you%20regarding%20your%20platform.'">Contact Us</button>

            </div>
            
            <div class="call-box">
                <h3 class="call-title">Or call</h3>
                <div class="call-number">
                    <i class="fas fa-phone phone-icon"></i>
                    <span>+216 00 000 000</span>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.querySelectorAll('.faq-item').forEach(item => {
            const question = item.querySelector('.faq-question');
            const icon = question.querySelector('i');
            
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                
                document.querySelectorAll('.faq-item').forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                        otherItem.querySelector('.faq-question i').className = 'fas fa-plus';
                    }
                });
                
                if (isActive) {
                    item.classList.remove('active');
                    icon.className = 'fas fa-plus';
                } else {
                    item.classList.add('active');
                    icon.className = 'fas fa-minus';
                }
            });
        });
    </script>
</body>
</html>