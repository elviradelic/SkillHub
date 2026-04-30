import { Link } from "react-router-dom";
import "./CourseCard.css";

function CourseCard({ course }) {
  return (
    <div className="course-card">
      <div className="course-card-header">
        <h3>{course.title}</h3>
      </div>
      <div className="course-card-body">
        <p>{course.description}</p>
        <p className="course-price">${course.price}</p>
      </div>
      <div className="course-card-footer">
        <Link to={`/courses/${course.id}`} className="btn-details">
          View Details
        </Link>
      </div>
    </div>
  );
}

export default CourseCard;