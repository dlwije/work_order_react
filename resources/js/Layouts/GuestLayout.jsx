import { Link } from '@inertiajs/react';
import ApplicationLogo from '@/Components/ApplicationLogo';

export default function GuestLayout({ children }) {
    return (
        <div className="login-page bg-body-secondary d-flex align-items-center justify-content-center min-vh-100">
            <div className="login-box">
                {/*<div className="login-logo mb-4 text-center">*/}
                {/*    <Link href="/" className="text-decoration-none fw-bold fs-2 text-dark">*/}
                {/*        <ApplicationLogo className="me-2" />*/}
                {/*        <b>My</b>App*/}
                {/*    </Link>*/}
                {/*</div>*/}

                <div className="card">
                    <div className="card-body login-card-body">{children}</div>
                </div>
            </div>
        </div>
    );
}
