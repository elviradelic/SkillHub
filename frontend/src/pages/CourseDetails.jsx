import { useParams } from "react-router-dom";
import { useEffect, useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import "./CourseDetails.css";

function CourseDetails() {
  const { id } = useParams();
  const [course, setCourse] = useState(null);
  const [lessons, setLessons] = useState([]);
  const [reviews, setReviews] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [enrolled, setEnrolled] = useState(false);
  const [reviewComment, setReviewComment] = useState("");
  const [reviewRating, setReviewRating] = useState(5);

  const user = JSON.parse(localStorage.getItem("user"));

  useEffect(() => {
    skillHubFacade
      .getCourseById(id)
      .then((res) => {
        setCourse(res.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Failed to load course.");
        setLoading(false);
      });

    skillHubFacade
      .getLessonsByCourse(id)
      .then((res) => {
        setLessons(res.data.data || res.data);
      })
      .catch(() => {
        setLessons([]);
      });

    skillHubFacade
      .getCourseReviews(id)
      .then((res) => {
        setReviews(res.data.data || []);
      })
      .catch(() => {
        setReviews([]);
      });
  }, [id]);

  const handleEnroll = () => {
    if (!user) {
      alert("Please log in to enroll.");
      return;
    }

    skillHubFacade
      .enrollInCourse({ user_id: user.id, course_id: Number(id) })
      .then(() => {
        setEnrolled(true);
      })
      .catch(() => {
        setError("Enrollment failed or already exists.");
      });
  };

  const handleReviewSubmit = (e) => {
    e.preventDefault();

    if (!user) {
      alert("Please log in to leave a review.");
      return;
    }

    skillHubFacade
      .addReview({
        user_id: user.id,
        course_id: Number(id),
        rating: reviewRating,
        comment: reviewComment,
      })
      .then(() => {
        setReviewComment("");
        setReviewRating(5);
        return skillHubFacade.getCourseReviews(id);
      })
      .then((res) => {
        setReviews(res.data.data || []);
      })
      .catch(() => {
        alert("Failed to submit review.");
      });
  };

  if (loading) return <p className="loading">Loading course...</p>;
  if (error) return <p className="error">{error}</p>;

  return (
    <div className="course-details">
      <div className="course-details-header">
        <h1>{course.title}</h1>
        <p>{course.description}</p>
        <p className="course-details-price">${course.price}</p>

        {enrolled ? (
          <p className="enrolled-msg">You are enrolled in this course!</p>
        ) : (
          <button className="btn-enroll" onClick={handleEnroll}>
            Enroll Now
          </button>
        )}

        <a href={`/quiz/${id}`} className="btn-quiz">
          Take Quiz
        </a>
      </div>

      <div className="course-lessons">
        <h2>Lessons</h2>
        {lessons.length > 0 ? (
          <ul>
            {lessons.map((lesson) => (
              <li key={lesson.id}>{lesson.title}</li>
            ))}
          </ul>
        ) : (
          <p>No lessons available yet.</p>
        )}
      </div>

      <div className="course-reviews">
        <h2>Reviews</h2>
        {reviews.length > 0 ? (
          reviews.map((review) => (
            <div key={review.id} className="review-card">
              <p className="review-author">{review.user_name}</p>
              <p className="review-rating">{review.rating} / 5</p>
              <p className="review-comment">{review.comment}</p>
            </div>
          ))
        ) : (
          <p>No reviews yet.</p>
        )}

        {user && (
          <form className="review-form" onSubmit={handleReviewSubmit}>
            <h3>Leave a Review</h3>
            <select
              value={reviewRating}
              onChange={(e) => setReviewRating(Number(e.target.value))}
            >
              <option value={5}>5 / 5</option>
              <option value={4}>4 / 5</option>
              <option value={3}>3 / 5</option>
              <option value={2}>2 / 5</option>
              <option value={1}>1 / 5</option>
            </select>
            <textarea
              value={reviewComment}
              onChange={(e) => setReviewComment(e.target.value)}
              placeholder="Write your review..."
              rows={4}
            />
            <button type="submit" className="btn-review">
              Submit Review
            </button>
          </form>
        )}
      </div>
    </div>
  );
}

export default CourseDetails;