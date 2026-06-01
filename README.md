# Warung Lesehan Yogyakarta — Restaurant Management System

End-to-end restaurant management platform handling 300+ daily 
transactions across 4 interconnected roles in real-time. Built after 
identifying critical operational inefficiencies during a university 
business flow analysis project.

## Tech Stack
- **Backend**: Laravel (PHP)
- **Frontend**: Vue.js
- **Database**: MySQL
- **Deployment**: VPS (Live)

## Role-Based System
| Role | Responsibilities |
|------|-----------------|
| Cashier | Receive & process customer orders |
| Kitchen | Receive real-time order notifications, manage preparation |
| Finance/Supplier | Track daily cash flow, manage supplier procurement |
| Owner | Full financial reports & operational overview |

## Features
- Real-time order flow: Cashier → Kitchen → Finance
- Daily financial reporting & transaction history
- Supplier procurement management when stock is low
- Owner dashboard with full financial visibility
- Eliminated 100% manual cash flow recording

## Highlights
- Handles 300+ daily transactions across all roles
- Trained 8–15 non-technical staff, full adoption within 1 week
- Awarded best team in university business case competition
- Deployed live on VPS

## System Flow
```
Customer Order → Cashier → Kitchen (real-time notification)
                         → Finance (transaction recorded)
                         → Owner (visible in report)
Finance → Supplier (procurement trigger when stock low)
```