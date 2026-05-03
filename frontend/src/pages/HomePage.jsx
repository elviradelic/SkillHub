import { Link } from "react-router-dom";
import "./HomePage.css";

function HomePage() {
  return (
    <div className="home">
      <section className="hero">
        <div className="hero-content">
          <h1><span>Welcome to </span><span>SkillHub</span></h1>
          <p>
            Discover courses, learn new skills, and grow your knowledge
            with our e-learning platform.
          </p>
          <Link to="/courses" className="btn-primary">
            Browse Courses
          </Link>
        </div>
      </section>

      <section className="features">
        <div className="feature-card">
          <h3>📚 Wide Range of Courses</h3>
          <p>Explore courses across many categories and topics.</p>
        </div>
        <div className="feature-card">
          <h3>🎓 Learn at Your Own Pace</h3>
          <p>Access lessons anytime and learn on your schedule.</p>
        </div>
        <div className="feature-card">
          <h3>📜 Earn Certificates</h3>
          <p>Complete courses and receive certificates of achievement.</p>
        </div>
      </section>
    </div>
  );
}

export default HomePage;