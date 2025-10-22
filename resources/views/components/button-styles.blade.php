{{-- Button Styles Component - Include in all layouts for uniform styling --}}
<style>
/* ========================================
   UNIFORM BUTTON STYLES FOR VOTING SYSTEM
   ======================================== */

/* Base Button Styles */
.btn {
    transition: all 0.3s ease !important;
    border-radius: 0.5rem !important;
    font-weight: 500 !important;
    padding: 0.5rem 1rem !important;
    border: 2px solid transparent !important;
    text-transform: none !important;
    letter-spacing: 0.025em !important;
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.btn:hover {
    transform: translateY(-1px) !important;
    text-decoration: none !important;
}

.btn:active {
    transform: translateY(0) !important;
}

.btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
}

/* Primary Buttons */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border-color: transparent !important;
    color: white !important;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
}

.btn-primary:focus {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
}

/* Success Buttons */
.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    border-color: transparent !important;
    color: white !important;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4) !important;
}

.btn-success:focus {
    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25) !important;
}

/* Warning Buttons */
.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    border-color: transparent !important;
    color: white !important;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4) !important;
}

/* Danger Buttons */
.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    border-color: transparent !important;
    color: white !important;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4) !important;
}

/* Info Buttons */
.btn-info {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
    border-color: transparent !important;
    color: white !important;
}

.btn-info:hover {
    background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4) !important;
}

/* Secondary Buttons */
.btn-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
    border-color: transparent !important;
    color: white !important;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #4b5563 0%, #374151 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4) !important;
}

/* Outline Button Styles */
.btn-outline-primary {
    background: transparent !important;
    border-color: #667eea !important;
    color: #667eea !important;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
}

.btn-outline-success {
    background: transparent !important;
    border-color: #10b981 !important;
    color: #10b981 !important;
}

.btn-outline-success:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4) !important;
}

.btn-outline-warning {
    background: transparent !important;
    border-color: #f59e0b !important;
    color: #f59e0b !important;
}

.btn-outline-warning:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4) !important;
}

.btn-outline-danger {
    background: transparent !important;
    border-color: #ef4444 !important;
    color: #ef4444 !important;
}

.btn-outline-danger:hover {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4) !important;
}

.btn-outline-info {
    background: transparent !important;
    border-color: #06b6d4 !important;
    color: #06b6d4 !important;
}

.btn-outline-info:hover {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4) !important;
}

.btn-outline-secondary {
    background: transparent !important;
    border-color: #6b7280 !important;
    color: #6b7280 !important;
}

.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4) !important;
}

/* Button Sizes */
.btn-lg {
    padding: 0.75rem 1.5rem !important;
    font-size: 1.1rem !important;
    border-radius: 0.6rem !important;
}

.btn-sm {
    padding: 0.375rem 0.75rem !important;
    font-size: 0.875rem !important;
    border-radius: 0.4rem !important;
}

/* Button Groups */
.btn-group .btn {
    border-radius: 0 !important;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.5rem !important;
    border-bottom-left-radius: 0.5rem !important;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.5rem !important;
    border-bottom-right-radius: 0.5rem !important;
}

.btn-group .btn:hover {
    transform: none !important;
    z-index: 1;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .btn {
        padding: 0.6rem 1.2rem !important;
        font-size: 0.9rem !important;
    }
    
    .btn-lg {
        padding: 0.8rem 1.6rem !important;
        font-size: 1rem !important;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem !important;
        font-size: 0.8rem !important;
    }
}

/* Accessibility Improvements */
.btn:focus-visible {
    outline: 2px solid currentColor !important;
    outline-offset: 2px !important;
}

.btn[disabled],
.btn:disabled {
    opacity: 0.6 !important;
    pointer-events: none !important;
    transform: none !important;
    box-shadow: none !important;
}

/* Icon Button Spacing */
.btn i + * {
    margin-left: 0.5rem;
}

.btn * + i {
    margin-left: 0.5rem;
}

/* Ensure text doesn't wrap in buttons */
.btn {
    white-space: nowrap;
}

/* Loading state */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: button-loading-spinner 1s ease infinite;
}

@keyframes button-loading-spinner {
    from {
        transform: rotate(0turn);
    }
    to {
        transform: rotate(1turn);
    }
}
</style>