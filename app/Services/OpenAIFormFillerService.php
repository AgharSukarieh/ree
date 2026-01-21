<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OpenAIFormFillerService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->model = 'gpt-4o-2024-08-06'; // Use latest model with structured outputs
        
        if (empty($this->apiKey)) {
            throw new Exception('OpenAI API key is not configured. Please set OPENAI_API_KEY in your .env file.');
        }
    }

    /**
     * Build SHORT and FOCUSED system prompt
     */
    private function buildSystemPrompt(): string
    {
        return <<<PROMPT
# Expert CV Analyzer - STRICT MODE

You are a professional CV analyzer. Your primary function is to extract information and classify it with 100% accuracy according to the rules. Failure to follow classification rules is not an option.

## CRITICAL RULES (NON-NEGOTIABLE):

### 1. Category Classification (MANDATORY & STRICT)

**EVERY SINGLE skill in `itSkills` MUST have a `category_id`. This is your most important task.**

**ABSOLUTE RULES - YOU MUST FOLLOW THESE EXACTLY:**

- **Git, GitHub, Version Control, Source Control**: `category_id` MUST BE 20. NO EXCEPTIONS. NEVER empty. NEVER null. NEVER other categories.

- **HTML, CSS**: `category_id` MUST BE 2. NEVER 4. NEVER Database. NEVER other categories.

- **SQL**: `category_id` MUST BE 4. NEVER 13. NEVER Teamworking. NEVER other categories.

- **MVC Pattern**: `category_id` MUST BE 2. NEVER 18. NEVER Database. NEVER other categories.

- **Android SDK, Android Native**: `category_id` MUST BE 3. NEVER 9. NEVER Design. NEVER other categories.

- **UI/UX Design, Figma, Adobe XD, Sketch, Responsive Design, UI Design, UX Design, User Interface Design, User Experience Design**: `category_id` MUST BE 9. NEVER 3. NEVER 2. NEVER App Development. NEVER Teamworking. NEVER other categories.

- **Dart, Data Structures**: `category_id` MUST BE 1. NEVER other categories.

- **Firebase**: `category_id` MUST BE 4 or 3. NEVER other categories.

### 2. Content & Accuracy

- Generate a professional `profile_summary` (3-4 sentences).

- Ensure `softSkills` has a minimum of 7 items.

- Ensure major-specific skills (like `itSkills`) have a minimum of 15 items.

- Use English for all text values.

- Set missing optional fields to `null`.

### 3. Major-Specific Sections

- **IT major**: You MUST fill `itSkills` (min 15), `itProjects`, `itAnalytical`. Other major skills (medical, business, engineering) MUST be `null`.

- **Medicine major**: You MUST fill `medicalSkills` (min 15), `medicalResearch`. Other major skills MUST be `null`.

- **Business major**: You MUST fill `businessSkills` (min 15), `businessCompetencies`, `businessInterests`. Other major skills MUST be `null`.

- **Engineering major**: You MUST fill `engineeringSkills` (min 15). Other major skills MUST be `null`.

{$this->getCompactCategories()}
PROMPT;
    }

    /**
     * Get compact category mapping
     */
    private function getCompactCategories(): string
    {
        return <<<CATEGORIES
## IT Categories (1-24):

1: Programming (Python, Java, JS, PHP, Dart, C++, OOP, Data Structures)
2: Web Dev (HTML, CSS, React, Vue, MVC, Bootstrap)
3: Mobile (Flutter, React Native, Android SDK, Android Native, iOS)
4: Database (MySQL, PostgreSQL, MongoDB, SQL, Firebase as DB)
5: DevOps (Docker, Kubernetes, AWS, CI/CD)
6: Data Science (Pandas, NumPy, Tableau)
7: AI/ML (TensorFlow, PyTorch, NLP)
8: Security (Penetration Testing, Encryption)
9: UI/UX Design (Figma, Adobe XD, Sketch, UI Design, UX Design, User Interface Design, User Experience Design, Responsive Design) **MANDATORY FOR UI/UX SKILLS**
10: Project Mgmt (Agile, Scrum, Jira)
11: QA (Selenium, Jest, Testing)
12: System Admin (Linux, Bash, PowerShell)
13: Network (TCP/IP, DNS, VPN)
14: Game Dev (Unity, Unreal)
15: Blockchain (Solidity, Ethereum)
16: IoT (Arduino, Raspberry Pi)
17: AR/VR (Unity VR, ARKit)
18: Microservices (Service Mesh, API Gateway)
19: API Dev (REST, GraphQL, Postman)
20: Version Control (Git, GitHub, GitLab, Source Control, Version Control Systems) **MANDATORY FOR GIT/GITHUB/SOURCE CONTROL**
21: Testing Frameworks (Jest, Mocha, JUnit)
22: Performance (Optimization, Caching)
23: Code Review (Static Analysis, SonarQube)
24: Documentation (Technical Writing)

## Medical Categories (1-16):

1: Clinical, 2: Diagnostic, 3: Surgical, 4: Emergency
5: Pediatric, 6: Geriatric, 7: Mental Health, 8: Radiology
9: Pathology, 10: Pharmacology, 11: Cardiology, 12: Neurology
13: Oncology, 14: Dermatology, 15: Orthopedics, 16: Ophthalmology

## Business Categories (24-39):

25: Legal Research, 26: Case Analysis, 27: Accounting Software
28: Financial Reporting, 29: Business Strategy, 30: Market Analysis
31: HR Management, 32: Teaching, 33: Educational Planning
34: Negotiation, 35: Leadership, 36: Project Coordination
37: Public Speaking, 38: Time Management, 39: Critical Thinking
24: Other

## Engineering Categories (8-16, 24):

8: CAD (AutoCAD, SolidWorks), 9: 3D Modeling (Blender)
10: Simulation (FEA, ANSYS), 11: Technical Drawing
12: Manufacturing, 13: Control Systems, 14: BIM (Revit)
15: Robotics, 16: Electrical Design, 24: Other
CATEGORIES;
    }

    /**
     * OLD METHOD - Keeping for reference but not used
     */
    private function buildSystemPromptOld(): string
    {
        $skillCategories = "
        ============================================
        IT SKILL CATEGORIES (1-24) - DETAILED MAPPING
        ============================================
        
        **Category 1: Programming Languages**
        Examples: Python, Java, JavaScript, C++, C#, Go, Rust, Swift, Kotlin, PHP, Ruby, TypeScript, Scala, R, MATLAB, Dart, Programming Languages, Data Structures, Algorithms, Object-Oriented Programming, Functional Programming
        Rule: Any general-purpose programming language (Java, Python, JavaScript, PHP, Dart, etc.), scripting language, or fundamental programming concepts (Data Structures, Algorithms, OOP, Functional Programming). This includes ALL programming languages regardless of their use case.
        
        **Category 2: Web Development**
        Examples: HTML, CSS, React, Vue.js, Angular, Svelte, Next.js, Nuxt.js, Webpack, Vite, Tailwind CSS, Bootstrap, Sass, Less, MVC Pattern, Model-View-Controller, Web Frameworks, Web Architecture Patterns, HTML5, CSS3, Responsive Web Design, Web Standards
        Rule: Technologies specifically for building web applications. This includes: HTML, CSS (ALWAYS Category 2, NEVER Database), frontend frameworks, CSS frameworks, web standards, web architecture patterns (MVC Pattern is ALWAYS Category 2, NEVER Database). HTML and CSS are web markup and styling languages, NOT databases.
        
        **Category 3: Mobile Development**
        Examples: Flutter, React Native, Swift (iOS), Kotlin (Android), Xamarin, Ionic, Android SDK, iOS SDK, React Native, NativeScript, Android Development, iOS Development, Mobile App Development, Cross-Platform Development, Firebase (when used for mobile), Mobile Frameworks
        Rule: Technologies and frameworks for mobile app development (iOS, Android, cross-platform). This includes Android SDK (ALWAYS Category 3, NEVER Design or UI/UX), iOS SDK, mobile frameworks, and mobile-specific tools. Firebase can be Category 3 when used for mobile development, or Category 4 when used as a database.
        
        **Category 4: Database Management**
        Examples: MySQL, PostgreSQL, MongoDB, SQLite, Oracle, SQL Server, Redis, Cassandra, DynamoDB, Firebase, Firebase Realtime Database, Firebase Firestore, Elasticsearch, SQL, NoSQL, Database Design, Database Administration, Query Languages
        Rule: Database systems, query languages (SQL is ALWAYS Category 4, NEVER Networking), NoSQL databases, database administration tools, and database platforms (Firebase when used as a database). SQL is a database query language, NOT a networking technology.
        
        **Category 5: DevOps & Cloud**
        Examples: Docker, Kubernetes, AWS, Azure, GCP, Terraform, Ansible, Jenkins, CI/CD, GitLab CI, GitHub Actions, CloudFormation
        Rule: Cloud platforms, containerization, infrastructure as code, deployment automation, cloud services.
        
        **Category 6: Data Science & Analytics**
        Examples: Pandas, NumPy, Matplotlib, Seaborn, Tableau, Power BI, Excel Analytics, SQL Analytics, Data Visualization, Statistical Analysis
        Rule: Tools and libraries for data analysis, visualization, and statistical computing.
        
        **Category 7: Machine Learning & AI**
        Examples: TensorFlow, PyTorch, Scikit-learn, Keras, OpenAI API, NLP, Computer Vision, Deep Learning, Neural Networks, ML Algorithms
        Rule: Machine learning frameworks, AI libraries, neural networks, natural language processing, computer vision.
        
        **Category 8: Cybersecurity**
        Examples: Penetration Testing, Ethical Hacking, Network Security, Encryption, SSL/TLS, OWASP, Security Auditing, Vulnerability Assessment, Firewall Management
        Rule: Security practices, tools, and knowledge related to protecting systems and data.
        
        **Category 9: UI/UX Design**
        Examples: Figma, Adobe XD, Sketch, InVision, Prototyping, User Research, Wireframing, Design Systems, Accessibility Design, Responsive Design, Responsive Web Design, UI Design, UX Design, User Interface Design, User Experience Design, Mobile UI Design, Web UI Design
        Rule: Design tools and methodologies for user interface and user experience design. This includes Responsive Design (ALWAYS Category 9, NEVER App Development or Mobile Development), UI/UX tools, prototyping tools, and design methodologies.
        
        **Category 10: Project Management**
        Examples: Agile, Scrum, Kanban, Jira, Trello, Asana, Project Planning, Sprint Management, Product Management, PMP
        Rule: Project management methodologies, tools, and frameworks.
        
        **Category 11: Quality Assurance**
        Examples: Selenium, Jest, Cypress, JUnit, TestNG, Manual Testing, Automated Testing, QA Testing, Bug Tracking, Test Planning
        Rule: Testing frameworks, QA methodologies, and testing tools.
        
        **Category 12: System Administration**
        Examples: Linux Administration, Windows Server, Shell Scripting, Bash, PowerShell, Server Management, System Monitoring, Backup & Recovery
        Rule: Operating system administration, server management, system maintenance.
        
        **Category 13: Network Administration**
        Examples: TCP/IP, DNS, DHCP, VPN, Network Configuration, Network Troubleshooting, Router Configuration, Switch Management, Network Security
        Rule: Network protocols, network infrastructure management, network troubleshooting.
        
        **Category 14: Game Development**
        Examples: Unity, Unreal Engine, GameMaker, Godot, C# (for games), Game Design, 3D Game Development, Game Physics
        Rule: Game engines, game development frameworks, and game-specific technologies.
        
        **Category 15: Blockchain & Cryptocurrency**
        Examples: Solidity, Ethereum, Smart Contracts, Blockchain Development, Web3, Cryptocurrency, DeFi, NFT Development, Hyperledger
        Rule: Blockchain technologies, cryptocurrency development, smart contracts.
        
        **Category 16: IoT Development**
        Examples: Arduino, Raspberry Pi, Embedded Systems, IoT Protocols (MQTT, CoAP), Sensor Integration, Edge Computing, IoT Platforms
        Rule: Internet of Things development, embedded systems, IoT platforms.
        
        **Category 17: AR/VR Development**
        Examples: Unity VR, Unreal Engine VR, ARKit, ARCore, Oculus Development, VR Development, Augmented Reality, Virtual Reality
        Rule: Augmented and virtual reality development tools and frameworks.
        
        **Category 18: Microservices Architecture**
        Examples: Microservices Design, Service Mesh, API Gateway, Distributed Systems, Service-Oriented Architecture (SOA), Container Orchestration, Microservices Patterns, Service Architecture
        Rule: Architectural patterns and technologies for microservices-based systems. Note: MVC Pattern belongs to Category 2 (Web Development), not this category.
        
        **Category 19: API Development**
        Examples: REST APIs, GraphQL, API Design, Postman, Swagger, OpenAPI, SOAP, API Documentation, API Testing, Web Services
        Rule: API design, development, testing, and documentation tools and practices.
        
        **Category 20: Version Control (MANDATORY for Git, GitHub, Version Control)**
        Examples: Git, GitHub, GitLab, Bitbucket, SVN, Mercurial, Git Workflows, Branching Strategies, Code Review Tools, Version Control, Source Control, Git Commands, Git Operations, Repository Management, Git Branching, Git Merging, Git Pull Requests, Git Issues, Git Actions, GitHub Actions, GitLab CI/CD
        Rule: Version control systems (Git, SVN, Mercurial), version control platforms (GitHub, GitLab, Bitbucket), and all version control-related workflows, commands, and operations. CRITICAL: Git and GitHub MUST ALWAYS be Category 20. If a skill is Git or GitHub or Version Control, it MUST be Category 20. NEVER leave Git or GitHub without a category. NEVER use Category 24 (Other) for Git or GitHub. If you cannot determine the category, use Category 20 for Git/GitHub/Version Control.
        
        **Category 21: Testing Frameworks**
        Examples: Jest, Mocha, Chai, Jasmine, PHPUnit, RSpec, JUnit, TestNG, Pytest, Unit Testing, Integration Testing
        Rule: Testing frameworks and testing methodologies (unit, integration, e2e).
        
        **Category 22: Performance Optimization**
        Examples: Code Optimization, Database Optimization, Caching Strategies, Load Balancing, Performance Monitoring, Profiling Tools
        Rule: Techniques and tools for optimizing application and system performance.
        
        **Category 23: Code Review**
        Examples: Code Review Practices, Peer Review, Static Code Analysis, Code Quality Tools, SonarQube, Code Standards
        Rule: Code review processes, code quality tools, and best practices.
        
        **Category 24: Documentation**
        Examples: Technical Writing, API Documentation, Code Documentation, Markdown, Documentation Tools, User Guides, Technical Documentation
        Rule: Documentation tools, practices, and methodologies ,git, github, version control.
        
        ============================================
        MEDICINE SKILL CATEGORIES (1-16) - DETAILED MAPPING
        ============================================
        
        **Category 1: Clinical Skills**
        Examples: Patient Assessment, Physical Examination, Medical History Taking, Clinical Reasoning, Bedside Manner, Patient Communication
        Rule: Direct patient care skills, clinical examination techniques, patient interaction.
        
        **Category 2: Diagnostic Skills**
        Examples: Diagnostic Reasoning, Differential Diagnosis, Medical Imaging Interpretation, Lab Results Analysis, Diagnostic Procedures
        Rule: Skills related to diagnosing medical conditions, interpreting diagnostic tests.
        
        **Category 3: Surgical Skills**
        Examples: Surgical Procedures, Operating Room Skills, Surgical Techniques, Minimally Invasive Surgery, Surgical Planning
        Rule: Surgical procedures, operating room skills, surgical techniques.
        
        **Category 4: Emergency Medicine**
        Examples: Emergency Response, Trauma Care, CPR, ACLS, Emergency Procedures, Triage, Critical Care
        Rule: Emergency medical procedures, trauma care, critical care skills.
        
        **Category 5: Pediatric Care**
        Examples: Pediatric Medicine, Child Health, Neonatal Care, Pediatric Procedures, Child Development Assessment
        Rule: Medical skills specific to children and infants.
        
        **Category 6: Geriatric Care**
        Examples: Elderly Care, Geriatric Medicine, Age-Related Conditions, Senior Health Management, Geriatric Assessment
        Rule: Medical skills specific to elderly patients.
        
        **Category 7: Mental Health**
        Examples: Psychiatry, Psychological Assessment, Mental Health Counseling, Therapy, Mental Health Disorders, Psychiatric Evaluation
        Rule: Mental health assessment, psychiatric care, psychological interventions.
        
        **Category 8: Radiology**
        Examples: Medical Imaging, X-Ray Interpretation, CT Scan, MRI Interpretation, Ultrasound, Radiographic Analysis
        Rule: Medical imaging techniques and interpretation.
        
        **Category 9: Pathology**
        Examples: Laboratory Medicine, Histopathology, Cytology, Laboratory Analysis, Tissue Examination, Lab Diagnostics
        Rule: Laboratory medicine, tissue analysis, pathological examination.
        
        **Category 10: Pharmacology**
        Examples: Medication Management, Drug Interactions, Prescription Writing, Pharmacokinetics, Medication Safety, Drug Therapy
        Rule: Medication management, pharmacology knowledge, drug therapy.
        
        **Category 11: Cardiology**
        Examples: Cardiovascular Care, ECG Interpretation, Heart Disease Management, Cardiac Procedures, Cardiovascular Assessment
        Rule: Heart and cardiovascular system care and procedures.
        
        **Category 12: Neurology**
        Examples: Neurological Assessment, Brain Disorders, Neurological Examination, Neurological Procedures, Neuroimaging
        Rule: Nervous system care, neurological assessment and procedures.
        
        **Category 13: Oncology**
        Examples: Cancer Treatment, Oncology Care, Chemotherapy, Radiation Therapy, Cancer Diagnosis, Oncology Procedures
        Rule: Cancer care, oncology treatment, cancer-related procedures.
        
        **Category 14: Dermatology**
        Examples: Skin Conditions, Dermatological Procedures, Skin Examination, Dermatological Treatment, Skin Biopsy
        Rule: Skin care, dermatological procedures and treatments.
        
        **Category 15: Orthopedics**
        Examples: Musculoskeletal Care, Bone Fractures, Joint Procedures, Orthopedic Surgery, Physical Therapy, Rehabilitation
        Rule: Musculoskeletal system care, orthopedic procedures.
        
        **Category 16: Ophthalmology**
        Examples: Eye Care, Vision Assessment, Eye Procedures, Ophthalmological Examination, Eye Surgery, Vision Disorders
        Rule: Eye care, vision assessment, ophthalmological procedures.
        
        ============================================
        BUSINESS SKILL CATEGORIES (24, 25-39) - DETAILED MAPPING
        ============================================
        
        **Category 25: Legal Research**
        Examples: Legal Research, Case Law Research, Legal Writing, Legal Analysis, Statutory Research, Legal Documentation
        Rule: Legal research skills, legal analysis, legal writing.
        
        **Category 26: Case Analysis**
        Examples: Case Study Analysis, Business Case Analysis, Problem-Solving, Analytical Thinking, Case Evaluation
        Rule: Analyzing business or legal cases, case study methodologies.
        
        **Category 27: Accounting Software**
        Examples: QuickBooks, SAP, Oracle Financials, Xero, Accounting Software, Financial Software, ERP Systems
        Rule: Accounting and financial software tools.
        
        **Category 28: Financial Reporting**
        Examples: Financial Statements, Financial Analysis, Financial Reporting, Budgeting, Financial Planning, Financial Modeling
        Rule: Financial reporting, financial analysis, financial planning.
        
        **Category 29: Business Strategy**
        Examples: Strategic Planning, Business Development, Strategic Analysis, Business Planning, Competitive Analysis, Market Strategy
        Rule: Strategic business planning, business development, strategic analysis.
        
        **Category 30: Market Analysis**
        Examples: Market Research, Market Analysis, Consumer Behavior, Market Trends, Competitive Analysis, Industry Analysis
        Rule: Market research, market analysis, industry analysis.
        
        **Category 31: Human Resource Management**
        Examples: HR Management, Recruitment, Talent Acquisition, Employee Relations, Performance Management, HR Policies
        Rule: Human resources management, recruitment, employee management.
        
        **Category 32: Teaching Skills**
        Examples: Teaching, Curriculum Development, Educational Instruction, Training Delivery, Pedagogy, Educational Methods
        Rule: Teaching methodologies, educational instruction, training delivery.
        
        **Category 33: Educational Planning**
        Examples: Curriculum Planning, Educational Design, Learning Objectives, Educational Program Development, Instructional Design
        Rule: Educational planning, curriculum development, instructional design.
        
        **Category 34: Negotiation & Conflict Resolution**
        Examples: Negotiation, Conflict Resolution, Mediation, Dispute Resolution, Contract Negotiation, Diplomatic Skills
        Rule: Negotiation skills, conflict resolution, mediation.
        
        **Category 35: Leadership & Management**
        Examples: Leadership, Team Management, Organizational Management, Management Skills, Team Leadership, Management Strategies
        Rule: Leadership skills, management practices, team management.
        
        **Category 36: Project Coordination**
        Examples: Project Coordination, Project Planning, Project Management, Task Coordination, Resource Management, Project Execution
        Rule: Project coordination, project planning, task management.
        
        **Category 37: Public Speaking**
        Examples: Public Speaking, Presentation Skills, Communication Skills, Speech Delivery, Presentation Design, Oratory Skills
        Rule: Public speaking, presentation skills, communication skills.
        
        **Category 38: Time Management**
        Examples: Time Management, Task Prioritization, Productivity, Efficiency, Schedule Management, Deadline Management
        Rule: Time management skills, productivity techniques.
        
        **Category 39: Critical Thinking**
        Examples: Critical Thinking, Problem-Solving, Analytical Thinking, Logical Reasoning, Decision-Making, Problem Analysis
        Rule: Critical thinking, analytical reasoning, problem-solving.
        
        **Category 24: Other**
        Examples: Any business skill that doesn't fit the above categories.
        Rule: Use only when the skill doesn't match any specific category above.
        
        ============================================
        ENGINEERING SKILL CATEGORIES (8-16, 24) - DETAILED MAPPING
        ============================================
        
        **Category 8: CAD Software**
        Examples: AutoCAD, SolidWorks, CATIA, Inventor, Fusion 360, CAD Design, Technical Drawing Software, 2D/3D CAD
        Rule: Computer-aided design software and tools.
        
        **Category 9: 3D Modeling**
        Examples: 3D Modeling, 3D Design, Blender, Maya, 3ds Max, Rhino, SketchUp, 3D Visualization, 3D Rendering
        Rule: 3D modeling software and techniques.
        
        **Category 10: Simulation & Analysis**
        Examples: Finite Element Analysis (FEA), CFD Analysis, Simulation Software, ANSYS, MATLAB Simulink, Engineering Simulation
        Rule: Engineering simulation and analysis tools.
        
        **Category 11: Technical Drawing**
        Examples: Technical Drawing, Engineering Drawing, Blueprint Reading, Drafting, Engineering Graphics, Technical Sketching
        Rule: Technical drawing skills, engineering graphics.
        
        **Category 12: Manufacturing Tools**
        Examples: CNC Machining, Manufacturing Processes, Production Tools, Manufacturing Equipment, Industrial Manufacturing
        Rule: Manufacturing tools, production processes, manufacturing equipment.
        
        **Category 13: Control Systems**
        Examples: Control Systems, PLC Programming, Automation, Industrial Control, Process Control, Control Engineering
        Rule: Control systems, automation, process control.
        
        **Category 14: Building Information Modeling (BIM)**
        Examples: BIM, Revit, Building Design, Architectural Modeling, Construction Modeling, BIM Software
        Rule: Building information modeling tools and techniques.
        
        **Category 15: Robotics & Automation**
        Examples: Robotics, Robot Programming, Automation Systems, Industrial Robotics, Robotic Systems, Automation Engineering
        Rule: Robotics, automation systems, robotic programming.
        
        **Category 16: Electrical Design Tools**
        Examples: Electrical Design, Circuit Design, Electrical CAD, Electrical Engineering Software, Power Systems Design
        Rule: Electrical design tools, circuit design software.
        
        **Category 24: Other**
        Examples: Any engineering skill that doesn't fit the above categories.
        Rule: Use only when the skill doesn't match any specific category above.
        
        ============================================
        CRITICAL CATEGORIZATION RULES:
        ============================================
        1. **Exact Match First**: If a skill exactly matches an example, use that category.
        2. **Context Matters**: Consider the skill's primary use case and context.
        3. **Primary Function**: Categorize based on the skill's primary function, not secondary uses.
        4. **Specific Over General**: Choose the most specific category that fits.
        5. **No Guessing**: If uncertain between categories, choose the one with the closest examples.
        6. **Consistency**: Similar skills should be in the same category.
        ";

        $schema = $this->getJsonSchema();

        return "
        You are an intelligent assistant specialized in analyzing CV text and auto-filling a structured form.

        Your task is to analyze the user's self-introduction text and extract all relevant information to fill a CV form.

        Core Rules:
        1. The response MUST be a JSON object that strictly conforms to the provided JSON Schema.
        2. DO NOT include any extra text or explanation outside the JSON object.
        3. All field values in the JSON MUST be in **English**.
        4. Fill all common fields (name, job_title, education, experiences, etc.) based on a careful analysis of the user's text.
        5. For fields where information is missing (e.g., links, exact dates), set them to null as per the schema type.
        6. The major field MUST be one of: IT, Medicine, Business, Engineering.
        7. Based on the major field, fill the corresponding major-specific sections:
           - For IT major: Fill itSkills, itProjects, itAnalytical. Set medicalSkills, medicalResearch, businessSkills, businessCompetencies, businessInterests, engineeringSkills to null.
           - For Medicine major: Fill medicalSkills, medicalResearch. Set itSkills, itProjects, itAnalytical, businessSkills, businessCompetencies, businessInterests, engineeringSkills to null.
           - For Business major: Fill businessSkills, businessCompetencies, businessInterests. Set itSkills, itProjects, itAnalytical, medicalSkills, medicalResearch, engineeringSkills to null.
           - For Engineering major: Fill engineeringSkills. Set itSkills, itProjects, itAnalytical, medicalSkills, medicalResearch, businessSkills, businessCompetencies, businessInterests to null.

        Enhancement and Professionalism Rules (Crucial for CV/ATS):

        A. **Professional Summary**: You MUST generate a **highly professional, concise, and ATS-friendly** summary for the profile_summary field. The summary must highlight the user's major, key achievements, and career goals, even if the user's text is brief.

        B. **Skill Suggestion**: You MUST ensure the final list of skills is comprehensive and highly relevant to the user's major and interests.
           - **Soft Skills**: The final list in softSkills MUST contain a minimum of **7** skills. Infer and suggest additional, highly relevant skills if the user's text does not provide enough.
           - **Technical Skills (e.g., itSkills for IT major)**: The final list MUST contain a minimum of **15** skills. Infer and suggest additional, highly relevant skills if the user's text does not provide enough. These suggested skills must be added to the respective skill lists (e.g., itSkills).
           - **Medical Skills (e.g., medicalSkills for Medicine major)**: The final list MUST contain a minimum of **15** skills. Infer and suggest additional, highly relevant medical skills if the user's text does not provide enough. These suggested skills must be added to the medicalSkills list with the correct medical_category_id.
           - **Business Skills (e.g., businessSkills for Business major)**: The final list MUST contain a minimum of **15** skills. Infer and suggest additional, highly relevant business skills if the user's text does not provide enough. These suggested skills must be added to the businessSkills list with the correct business_category_id.
           - **Engineering Skills (e.g., engineeringSkills for Engineering major)**: The final list MUST contain a minimum of **15** skills. Infer and suggest additional, highly relevant engineering skills if the user's text does not provide enough. These suggested skills must be added to the engineeringSkills list with the correct engineering_category_id.

        C. **Job/Activity Description**: For experiences[*].description and activities[*].description_activity, you MUST generate detailed, achievement-oriented bullet points. Use strong **action verbs** and focus on the user's **active role and positive impact** to make the description highly effective for CVs and Applicant Tracking Systems (ATS).

        D. **Accuracy**: Ensure **accurate extraction and correct spelling** for all names, including degree_name, field_of_study, university_name, certifications_name, and issuing_org.

        E. **Skill Category Classification (CRITICAL - HIGHEST ACCURACY REQUIRED - MANDATORY FIELD)**: 
        
        **ABSOLUTE REQUIREMENT**: For EVERY skill in itSkills, medicalSkills, businessSkills, and engineeringSkills, you MUST provide a category_id. The category_id field is REQUIRED and CANNOT be null, empty, or missing. If you do not provide a category_id, the response will be invalid.
        
        **SPECIAL RULES FOR COMMON SKILLS (MUST FOLLOW):**
        - If skill_name is Git → category_id MUST be 20
        - If skill_name is GitHub → category_id MUST be 20
        - If skill_name is Version Control → category_id MUST be 20
        - If skill_name contains Git → category_id MUST be 20
        - If skill_name contains Version Control → category_id MUST be 20
        
        For ALL skills (itSkills, medicalSkills, businessSkills, engineeringSkills), you MUST select the **MOST ACCURATE** category_id based on the detailed mapping below. NEVER omit the category_id field.
        
        **Categorization Process (Follow Strictly):**
        1. **Read the skill name carefully** - Consider the exact skill name provided.
        2. **Match to Examples** - Check if the skill matches any example in the category list. If it matches exactly or closely, use that category.
        3. **Apply Category Rules** - Read the Rule section for each category to understand its scope.
        4. **Consider Context** - If a skill could fit multiple categories, choose based on:
           - The skill's PRIMARY function (not secondary uses)
           - The most specific category that fits
           - The category with the closest examples
        5. **Verify Accuracy** - Before finalizing, double-check that the category_id matches the skill's primary purpose.
        
        **Common Mistakes to AVOID (CRITICAL - READ CAREFULLY):**
        - ❌ Git, GitHub, Version Control → MUST be Category 20 (NEVER leave empty, NEVER Category 24)
        - ❌ HTML, CSS → MUST be Category 2 (Web Development), NEVER Category 4 (Database)
        - ❌ SQL → MUST be Category 4 (Database Management), NEVER Category 13 (Networking)
        - ❌ MVC Pattern → MUST be Category 2 (Web Development), NEVER Category 4 (Database)
        - ❌ Android SDK → MUST be Category 3 (Mobile Development), NEVER Category 9 (Design)
        - ❌ Firebase → Category 4 (Database) or Category 3 (Mobile), NEVER Category 13 (Networking)
        - ❌ Responsive Design → MUST be Category 9 (UI/UX Design), NEVER Category 3 (App Development)
        - ❌ Data Structures → MUST be Category 1 (Programming Languages), NEVER Category 7 (AI)
        - ❌ Dart → MUST be Category 1 (Programming Languages)
        - ❌ Don't use Category 24 (Other) unless the skill truly doesn't fit any specific category
        - ❌ Don't categorize based on secondary uses - use PRIMARY function only
        - ❌ Don't guess - if uncertain, compare with examples and rules
        - ❌ Don't use generic categories when specific ones exist
        - ❌ NEVER leave Git or GitHub without a category - ALWAYS use Category 20
        
        **Examples of Correct Categorization (MANDATORY REFERENCE - COPY THESE EXACTLY):**
        
        **IT Skills Examples (with category_id - NOTE: category_id is ALWAYS present):**
        - Python → Category 1 (Programming Languages) ✓
        - Java → Category 1 (Programming Languages) ✓
        - JavaScript → Category 1 (Programming Languages) ✓
        - PHP → Category 1 (Programming Languages) ✓
        - Dart → Category 1 (Programming Languages) ✓
        - Data Structures → Category 1 (Programming Languages) ✓
        - HTML → Category 2 (Web Development) ✓ (NEVER Database)
        - CSS → Category 2 (Web Development) ✓ (NEVER Database)
        - React → Category 2 (Web Development) ✓
        - MVC Pattern → Category 2 (Web Development) ✓ (NEVER Database)
        - Flutter → Category 3 (Mobile Development) ✓
        - Android SDK → Category 3 (Mobile Development) ✓ (NEVER Design)
        - Firebase → Category 4 (Database Management) or Category 3 (Mobile Development) ✓ (NEVER Networking)
        - MySQL → Category 4 (Database Management) ✓
        - SQL → Category 4 (Database Management) ✓ (NEVER Networking)
        - Docker → Category 5 (DevOps & Cloud) ✓
        - TensorFlow → Category 7 (Machine Learning & AI) ✓
        - Responsive Design → Category 9 (UI/UX Design) ✓ (NEVER App Development)
        - Git → Category 20 (Version Control) ✓ (MANDATORY - category_id MUST be 20, NEVER empty or null)
        - GitHub → Category 20 (Version Control) ✓ (MANDATORY - category_id MUST be 20, NEVER empty or null)
        - Version Control → Category 20 (Version Control) ✓ (MANDATORY - category_id MUST be 20, NEVER empty or null)
        
        **CRITICAL REMINDER**: For Git, GitHub, and Version Control, the category_id field MUST ALWAYS be 20. It CANNOT be null, empty, or missing. Every skill object MUST include both skill_name AND category_id.
        
        **Medical Skills Examples:**
        - Patient Assessment → Medical Category 1 (Clinical Skills) ✓
        - ECG Interpretation → Medical Category 11 (Cardiology) ✓
        
        **Business Skills Examples:**
        - QuickBooks → Business Category 27 (Accounting Software) ✓
        
        **Engineering Skills Examples:**
        - AutoCAD → Engineering Category 8 (CAD Software) ✓
        
        **Detailed Skill Category Mapping:**
        {$skillCategories}

        The Required JSON Schema:
        {$schema}
        ";
    }

    /**
     * Get the JSON Schema for form structure
     */
    private function getJsonSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'name' => ['type' => 'string', 'description' => 'Full Name.'],
                'jop_title' => ['type' => 'string', 'description' => 'Suggested Job Title, e.g., Junior Software Engineer.'],
                'phone' => ['type' => ['string', 'null'], 'description' => 'Phone Number.'],
                'email' => ['type' => ['string', 'null'], 'description' => 'Email Address.'],
                'city' => ['type' => 'string', 'description' => 'City and Country, e.g., Amman, Jordan.'],
                'major' => ['type' => 'string', 'enum' => ['IT', 'Medicine', 'Business', 'Engineering'], 'description' => 'Major Field.'],
                'linkedin_profile' => ['type' => ['string', 'null'], 'description' => 'LinkedIn Profile URL.'],
                'github_profile' => ['type' => ['string', 'null'], 'description' => 'GitHub Profile URL.'],
                'profile_summary' => ['type' => ['string', 'null'], 'description' => 'A highly professional, ATS-friendly, and compelling summary. GPT MUST generate this based on the user\'s text, major, and interests.'],
                'languages' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'language_name' => ['type' => 'string'],
                            'proficiency_level' => ['type' => 'string', 'enum' => ['Beginner', 'Intermediate', 'Advanced', 'Native']]
                        ],
                        'required' => ['language_name', 'proficiency_level'],
                        'additionalProperties' => false
                    ]
                ],
                'softSkills' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'soft_name' => ['type' => 'string', 'description' => 'Soft skill name. GPT MUST suggest additional, highly relevant skills based on the user\'s major and interests. The final list MUST contain at least 7 skills.']
                        ],
                        'required' => ['soft_name'],
                        'additionalProperties' => false
                    ],
                    'minItems' => 7,
                    'description' => 'List of soft skills. GPT MUST suggest additional, highly relevant skills. The final list MUST contain a minimum of 7 skills.'
                ],
                'experiences' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'company' => ['type' => 'string'],
                            'location' => ['type' => 'string'],
                            'start_date' => ['type' => 'string'],
                            'end_date' => ['type' => 'string'],
                            'description' => ['type' => 'string', 'description' => 'Job description in an impact-focused, achievement-oriented bullet point format, optimized for CV/ATS. GPT MUST suggest a detailed, professional description if the user\'s text is vague.'],
                            'is_internship' => ['type' => 'boolean']
                        ],
                        'required' => ['title', 'company', 'location', 'start_date', 'end_date', 'description', 'is_internship'],
                        'additionalProperties' => false
                    ]
                ],
                'education' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'degree_name' => ['type' => 'string', 'description' => 'Accurately extracted Degree Name, e.g., Bachelor\'s, Master\'s.'],
                            'field_of_study' => ['type' => 'string', 'description' => 'Accurately extracted Field of Study, e.g., Software Engineering.'],
                            'university_name' => ['type' => 'string', 'description' => 'Accurately extracted University Name.'],
                            'start_year' => ['type' => 'string'],
                            'end_year' => ['type' => 'string']
                        ],
                        'required' => ['degree_name', 'field_of_study', 'university_name', 'start_year', 'end_year'],
                        'additionalProperties' => false
                    ]
                ],
                'certifications' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'certifications_name' => ['type' => 'string', 'description' => 'Accurately extracted Certification Name with correct spelling.'],
                            'issuing_org' => ['type' => 'string', 'description' => 'Accurately extracted Issuing Organization Name with correct spelling.'],
                            'issue_date' => ['type' => 'string'],
                            'expiration_date' => ['type' => ['string', 'null']],
                            'link_driver' => ['type' => ['string', 'null']]
                        ],
                        'required' => ['certifications_name', 'issuing_org', 'issue_date', 'expiration_date', 'link_driver'],
                        'additionalProperties' => false
                    ]
                ],
                'activities' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'activity_title' => ['type' => 'string'],
                            'organization' => ['type' => 'string'],
                            'activity_date' => ['type' => 'string'],
                            'description_activity' => ['type' => 'string', 'description' => 'Activity description in an impact-focused, achievement-oriented bullet point format. GPT MUST suggest a detailed, professional description if the user\'s text is vague.'],
                            'activity_link' => ['type' => ['string', 'null']]
                        ],
                        'required' => ['activity_title', 'organization', 'activity_date', 'description_activity', 'activity_link'],
                        'additionalProperties' => false
                    ]
                ],
                'itSkills' => [
                    'type' => ['array', 'null'],
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'skill_name' => ['type' => 'string', 'description' => 'Technical skill name, e.g., Python, React. GPT MUST suggest additional, highly relevant skills based on the user\'s major and interests. The final list MUST contain at least 15 skills.'],
                            'category_id' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 24, 'description' => 'MANDATORY integer ID from 1-24. YOU MUST PROVIDE THIS. CRITICAL: Git/GitHub/Version Control/Source Control MUST be 20. HTML/CSS MUST be 2. SQL MUST be 4. MVC Pattern MUST be 2. Android SDK/Android Native MUST be 3. UI/UX/Figma/Adobe XD/Sketch/Responsive Design MUST be 9. Dart/Data Structures MUST be 1. NO MISTAKES ALLOWED.']
                        ],
                        'required' => ['skill_name', 'category_id'],
                        'additionalProperties' => false
                    ],
                    'minItems' => 15,
                    'description' => 'List of technical skills (IT Major only). GPT MUST suggest additional, highly relevant skills and include the category_id. The final list MUST contain a minimum of 15 skills.'
                ],
                'itProjects' => [
                    'type' => ['array', 'null'],
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'project_title' => ['type' => 'string'],
                            'technologies_used' => ['type' => 'string'],
                            'description_project' => ['type' => 'string'],
                            'link' => ['type' => ['string', 'null']]
                        ],
                        'required' => ['project_title', 'technologies_used', 'description_project', 'link'],
                        'additionalProperties' => false
                    ]
                ],
                'itAnalytical' => [
                    'type' => ['array', 'null'],
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'analytical_skill_name' => ['type' => 'string']
                        ],
                        'required' => ['analytical_skill_name'],
                        'additionalProperties' => false
                    ]
                ],
                'medicalSkills' => [
                    'type' => ['array', 'null'],
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'medical_skill_name' => ['type' => 'string', 'description' => 'Medical skill name, e.g., Surgery, Diagnosis. GPT MUST suggest additional, highly relevant medical skills based on the user\'s major and interests. The final list MUST contain at least 15 skills.'],
                            'medical_category_id' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 16, 'description' => 'Medical Skill Category ID (1-16). GPT MUST select the correct ID based on the skill.']
                        ],
                        'required' => ['medical_skill_name', 'medical_category_id'],
                        'additionalProperties' => false
                    ],
                    'minItems' => 15,
                    'description' => 'List of medical skills (Medicine Major only). GPT MUST suggest additional, highly relevant skills and include the medical_category_id. The final list MUST contain a minimum of 15 skills.'
                ],
                'medicalResearch' => [
                    'type' => ['array', 'null'],
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'research_title' => ['type' => 'string'],
                            'publication_year' => ['type' => 'string'],
                            'research_description' => ['type' => 'string'],
                            'research_link' => ['type' => ['string', 'null']]
                        ],
                        'required' => ['research_title', 'publication_year', 'research_description', 'research_link'],
                        'additionalProperties' => false
                    ]
                ],
                'businessSkills' => [
                    'type' => ['array', 'null'],
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'business_skill_name' => ['type' => 'string', 'description' => 'Business skill name. GPT MUST suggest additional, highly relevant skills based on the user\'s major and interests. The final list MUST contain at least 15 skills.'],
                            'business_category_id' => ['type' => 'integer', 'minimum' => 24, 'maximum' => 39, 'description' => 'Skill Category ID (24-39). GPT MUST select the correct ID based on the skill.']
                        ],
                        'required' => ['business_skill_name', 'business_category_id'],
                        'additionalProperties' => false
                    ],
                    'minItems' => 15,
                    'description' => 'List of business skills (Business Major only). GPT MUST suggest additional, highly relevant skills and include the category_id. The final list MUST contain a minimum of 15 skills.'
                ],
                'businessCompetencies' => [
                    'type' => ['array', 'null'],
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'competency_name' => ['type' => 'string'],
                            'competency_description' => ['type' => 'string']
                        ],
                        'required' => ['competency_name', 'competency_description'],
                        'additionalProperties' => false
                    ]
                ],
                'businessInterests' => [
                    'type' => ['array', 'null'],
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'interest_name' => ['type' => 'string']
                        ],
                        'required' => ['interest_name'],
                        'additionalProperties' => false
                    ]
                ],
                'engineeringSkills' => [
                    'type' => ['array', 'null'],
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'engineering_skill_name' => ['type' => 'string', 'description' => 'Engineering skill name. GPT MUST suggest additional, highly relevant skills based on the user\'s major and interests. The final list MUST contain at least 15 skills.'],
                            'engineering_category_id' => ['type' => 'integer', 'minimum' => 8, 'maximum' => 24, 'description' => 'Skill Category ID (8-16, 24). GPT MUST select the correct ID based on the skill.']
                        ],
                        'required' => ['engineering_skill_name', 'engineering_category_id'],
                        'additionalProperties' => false
                    ],
                    'minItems' => 15,
                    'description' => 'List of engineering skills (Engineering Major only). GPT MUST suggest additional, highly relevant skills and include the category_id. The final list MUST contain a minimum of 15 skills.'
                ]
            ],
            'required' => [
                'name', 
                'jop_title', 
                'phone', 
                'email', 
                'city', 
                'major', 
                'linkedin_profile', 
                'github_profile', 
                'profile_summary', 
                'languages', 
                'softSkills', 
                'experiences', 
                'education', 
                'certifications', 
                'activities',
                'itSkills',
                'itProjects',
                'itAnalytical',
                'medicalSkills',
                'medicalResearch',
                'businessSkills',
                'businessCompetencies',
                'businessInterests',
                'engineeringSkills'
            ],
            'additionalProperties' => false
        ];
    }

    /**
     * Fill form using OpenAI API
     * 
     * @param string $userIntroduction The user's self-introduction text
     * @return array|null The filled form data or null on failure
     */
    public function fillForm(string $userIntroduction): ?array
    {
        try {
            Log::info('Sending request to OpenAI', [
                'model' => $this->model,
                'prompt_length' => strlen($userIntroduction)
            ]);

            $payload = [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->buildSystemPrompt()
                    ],
                    [
                        'role' => 'user',
                        'content' => $userIntroduction
                    ]
                ],
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => [
                        'name' => 'cv_form_data',
                        'strict' => true, // **التعديل الثاني: تفعيل الوضع الصارم (strict) لإجبار النموذج على الالتزام بالقواعد**
                        'schema' => $this->getJsonSchema()
                    ]
                ],
                'temperature' => 0.1, // **Ultra-low temperature for maximum accuracy and consistency**
                'max_tokens' => 4096
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(90)->post("{$this->baseUrl}/chat/completions", $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Handle structured output response
                if (isset($responseData['choices'][0]['message']['content'])) {
                    $content = $responseData['choices'][0]['message']['content'];
                    $filledData = json_decode($content, true);
                } elseif (isset($responseData['choices'][0]['message']['refusal'])) {
                    // Handle refusal case
                    Log::warning('OpenAI refused to generate content', [
                        'refusal' => $responseData['choices'][0]['message']['refusal']
                    ]);
                    return null;
                } else {
                    Log::error('Unexpected response structure from OpenAI', [
                        'response' => $responseData
                    ]);
                    return null;
                }
                
                if (isset($filledData)) {
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        // **STEP 2: AUTO-CORRECTION LAYER - Final guarantee**
                        $correctedData = $this->autoCorrectSkillCategories($filledData);
                        
                        // Validate and log the corrected data
                        $this->validateResponse($correctedData);
                        
                        Log::info('Successfully received and corrected form data from OpenAI', [
                            'major' => $correctedData['major'] ?? null,
                            'skills_count' => $this->countSkills($correctedData)
                        ]);
                        
                        \Log::channel('single')->info('=== Corrected OpenAI Response ===');
                        \Log::channel('single')->info(json_encode($correctedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                        
                        return $correctedData; // Return the corrected data
                    } else {
                        Log::error('Failed to decode JSON response', [
                            'error' => json_last_error_msg(),
                            'content' => substr($content, 0, 500)
                        ]);
                        return null;
                    }
                } else {
                    Log::error('Invalid response structure from OpenAI', [
                        'response' => $responseData
                    ]);
                    return null;
                }
            } else {
                Log::error('OpenAI API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }
        } catch (Exception $e) {
            Log::error('Exception during OpenAI API call', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Validate response against requirements
     */
    private function validateResponse(array $data): void
    {
        $issues = [];

        // Check soft skills count
        if (isset($data['softSkills']) && count($data['softSkills']) < 7) {
            $issues[] = "Only " . count($data['softSkills']) . " soft skills (minimum 7)";
        }

        // Check technical skills based on major
        $major = $data['major'] ?? null;
        if ($major === 'IT' && isset($data['itSkills'])) {
            if (count($data['itSkills']) < 15) {
                $issues[] = "Only " . count($data['itSkills']) . " IT skills (minimum 15)";
            }
            
            // Check for Git/GitHub category
            foreach ($data['itSkills'] as $skill) {
                $skillName = strtolower($skill['skill_name'] ?? '');
                if (in_array($skillName, ['git', 'github', 'version control'])) {
                    if (($skill['category_id'] ?? null) !== 20) {
                        $issues[] = "Git/GitHub has wrong category_id: " . ($skill['category_id'] ?? 'null');
                    }
                }
            }
        }

        if ($major === 'Medicine' && isset($data['medicalSkills'])) {
            if (count($data['medicalSkills']) < 15) {
                $issues[] = "Only " . count($data['medicalSkills']) . " medical skills (minimum 15)";
            }
        }

        if ($major === 'Business' && isset($data['businessSkills'])) {
            if (count($data['businessSkills']) < 15) {
                $issues[] = "Only " . count($data['businessSkills']) . " business skills (minimum 15)";
            }
        }

        if ($major === 'Engineering' && isset($data['engineeringSkills'])) {
            if (count($data['engineeringSkills']) < 15) {
                $issues[] = "Only " . count($data['engineeringSkills']) . " engineering skills (minimum 15)";
            }
        }

        // Check profile summary
        if (empty($data['profile_summary'])) {
            $issues[] = "Missing profile_summary";
        }

        if (!empty($issues)) {
            Log::warning('Response validation issues:', $issues);
        }
    }

    /**
     * Count skills by major
     */
    private function countSkills(array $data): array
    {
        return [
            'soft' => count($data['softSkills'] ?? []),
            'it' => count($data['itSkills'] ?? []),
            'medical' => count($data['medicalSkills'] ?? []),
            'business' => count($data['businessSkills'] ?? []),
            'engineering' => count($data['engineeringSkills'] ?? [])
        ];
    }

    /**
     * Auto-corrects skill categories based on predefined strict rules.
     * This is the "final guarantee" layer that ensures 100% accuracy.
     *
     * @param array $data The data received from OpenAI.
     * @return array The corrected data.
     */
    private function autoCorrectSkillCategories(array $data): array
    {
        // Correct IT Skills
        if (isset($data['itSkills']) && is_array($data['itSkills'])) {
            foreach ($data['itSkills'] as $key => $skill) {
                if (!isset($skill['skill_name'])) {
                    continue;
                }
                
                $skillName = strtolower(trim($skill['skill_name']));
                $originalCategoryId = $skill['category_id'] ?? null;
                $correctedCategoryId = null;
                
                // Rule 1: Git, GitHub, Version Control, Source Control -> ALWAYS 20
                if (in_array($skillName, ['git', 'github', 'version control', 'source control']) || 
                    strpos($skillName, 'git') !== false || 
                    strpos($skillName, 'version control') !== false ||
                    strpos($skillName, 'source control') !== false) {
                    $correctedCategoryId = 20;
                }
                // Rule 2: HTML, CSS -> ALWAYS 2
                elseif (in_array($skillName, ['html', 'css'])) {
                    $correctedCategoryId = 2;
                }
                // Rule 3: SQL -> ALWAYS 4
                elseif ($skillName === 'sql') {
                    $correctedCategoryId = 4;
                }
                // Rule 4: MVC Pattern -> ALWAYS 2
                elseif ($skillName === 'mvc pattern' || $skillName === 'mvc') {
                    $correctedCategoryId = 2;
                }
                // Rule 5: Android SDK, Android Native -> ALWAYS 3
                elseif (in_array($skillName, ['android sdk', 'android native']) || 
                        strpos($skillName, 'android native') !== false) {
                    $correctedCategoryId = 3;
                }
                // Rule 6: UI/UX related -> ALWAYS 9
                elseif (in_array($skillName, ['figma', 'adobe xd', 'sketch', 'ui/ux design', 'responsive design', 
                        'ui design', 'ux design', 'user interface design', 'user experience design']) ||
                        strpos($skillName, 'ui/ux') !== false ||
                        strpos($skillName, 'ui design') !== false ||
                        strpos($skillName, 'ux design') !== false ||
                        strpos($skillName, 'responsive') !== false) {
                    $correctedCategoryId = 9;
                }
                // Rule 7: Dart, Data Structures -> ALWAYS 1
                elseif (in_array($skillName, ['dart', 'data structures'])) {
                    $correctedCategoryId = 1;
                }
                
                // Apply correction if needed
                if ($correctedCategoryId !== null && $originalCategoryId !== $correctedCategoryId) {
                    $data['itSkills'][$key]['category_id'] = $correctedCategoryId;
                    
                    Log::warning('Auto-corrected skill category', [
                        'skill_name' => $skill['skill_name'],
                        'original_category_id' => $originalCategoryId,
                        'corrected_category_id' => $correctedCategoryId
                    ]);
                }
                
                // Ensure category_id exists (if missing, try to infer from skill name)
                if (!isset($data['itSkills'][$key]['category_id']) || $data['itSkills'][$key]['category_id'] === null) {
                    if ($correctedCategoryId !== null) {
                        $data['itSkills'][$key]['category_id'] = $correctedCategoryId;
                        Log::warning('Added missing category_id for skill', [
                            'skill_name' => $skill['skill_name'],
                            'category_id' => $correctedCategoryId
                        ]);
                    }
                }
            }
        }
        
        // You can add similar correction logic for medicalSkills, businessSkills, engineeringSkills if needed
        
        return $data;
    }
}

