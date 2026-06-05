import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import skillHubFacade from "../services/skillHubFacade";
import "./Navbar.css";

function Navbar() {
  const user = JSON.parse(localStorage.getItem("user"));
  const [notifications, setNotifications] = useState([]);
  const [showNotifications, setShowNotifications] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);

  useEffect(() => {
    if (!user || user.role === "admin") return;

    const loadNotifications = () => {
      skillHubFacade
        .getUserNotifications(user.id)
        .then((res) => {
          setNotifications(res.data.data || []);
        })
        .catch(() => {
          setNotifications([]);
        });
    };

    loadNotifications();

    const interval = setInterval(loadNotifications, 3000);

    return () => clearInterval(interval);
  }, [user]);

  const handleLogout = () => {
    localStorage.removeItem("user");
    window.location.href = "/";
  };

  const closeMenu = () => {
    setMenuOpen(false);
    setShowNotifications(false);
  };

  return (
    <nav className="navbar">
      <div className="navbar-brand">
        <Link to="/" onClick={closeMenu}>
          SkillHub
        </Link>
      </div>

      <div className="navbar-mobile-actions">
        {user && user.role !== "admin" && (
          <div className="notification-wrapper mobile-notification">
            <button
              className="notification-button"
              onClick={() => setShowNotifications(!showNotifications)}
            >
              🔔
              {notifications.length > 0 && (
                <span className="notification-badge">
                  {notifications.length}
                </span>
              )}
            </button>

            {showNotifications && (
              <div className="notification-dropdown">
                <h4>Notifications</h4>

                {notifications.length === 0 ? (
                  <p>No notifications yet.</p>
                ) : (
                  notifications.map((notification) => (
                    <div
                      key={notification.id}
                      className="notification-item"
                    >
                      <p>{notification.message}</p>
                      <small>{notification.created_at}</small>
                    </div>
                  ))
                )}
              </div>
            )}
          </div>
        )}

        <button
          className="navbar-toggle"
           onClick={() => {
            setMenuOpen(!menuOpen);
            setShowNotifications(false);
            }}
>
            {menuOpen ? "✕" : "☰"}
         </button>
      </div>

      <div className={`navbar-links ${menuOpen ? "active" : ""}`}>
        <Link to="/" onClick={closeMenu}>
          Home
        </Link>

        <Link to="/courses" onClick={closeMenu}>
          Courses
        </Link>

        {user ? (
          <>
            {user.role === "student" && (
              <>
                <Link to="/my-courses" onClick={closeMenu}>
                  My Courses
                </Link>

                <Link to="/results" onClick={closeMenu}>
                  Results
                </Link>
              </>
            )}

            {user.role === "instructor" && (
              <Link to="/instructor" onClick={closeMenu}>
                Instructor Dashboard
              </Link>
            )}

            {user.role === "admin" && (
              <Link to="/admin" onClick={closeMenu}>
                Admin Dashboard
              </Link>
            )}

            {user.role !== "admin" && (
              <div className="notification-wrapper desktop-notification">
                <button
                  className="notification-button"
                  onClick={() => setShowNotifications(!showNotifications)}
                >
                  🔔
                  {notifications.length > 0 && (
                    <span className="notification-badge">
                      {notifications.length}
                    </span>
                  )}
                </button>

                {showNotifications && (
                  <div className="notification-dropdown">
                    <h4>Notifications</h4>

                    {notifications.length === 0 ? (
                      <p>No notifications yet.</p>
                    ) : (
                      notifications.map((notification) => (
                        <div
                          key={notification.id}
                          className="notification-item"
                        >
                          <p>{notification.message}</p>
                          <small>{notification.created_at}</small>
                        </div>
                      ))
                    )}
                  </div>
                )}
              </div>
            )}

            <span className="navbar-user">👤 {user.name}</span>

            <button className="btn-logout" onClick={handleLogout}>
              Logout
            </button>
          </>
        ) : (
          <>
            <Link to="/login" onClick={closeMenu}>
              Login
            </Link>

            <Link to="/register" onClick={closeMenu}>
              Register
            </Link>
          </>
        )}
      </div>
    </nav>
  );
}

export default Navbar;