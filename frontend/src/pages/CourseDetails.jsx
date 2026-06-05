import { useParams } from "react-router-dom";
import { useEffect, useState } from "react";
import skillHubFacade from "../services/skillHubFacade";
import "./CourseDetails.css";

function CourseDetails() {
  const { id } = useParams();
  const [course, setCourse] = useState(null);
  const [lessons, setLessons] = useState([]);
  const [materials, setMaterials] = useState([]);
  const [reviews, setReviews] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [enrolled, setEnrolled] = useState(false);
  const [enrollmentMessage, setEnrollmentMessage] = useState("");
  const [reviewComment, setReviewComment] = useState("");
  const [reviewRating, setReviewRating] = useState(5);
  const [openedLesson, setOpenedLesson] = useState(null);
  const [progress, setProgress] = useState(null);
  const [certificate, setCertificate] = useState(null);
  const [certificateMessage, setCertificateMessage] = useState("");
  const [lastLesson, setLastLesson] = useState(null);

  const user = JSON.parse(localStorage.getItem("user"));

  const canAccessContent =
    user && (enrolled || user.role === "instructor" || user.role === "admin");

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
      .getCourseReviews(id)
      .then((res) => setReviews(res.data.data || []))
      .catch(() => setReviews([]));

    if (user) {
      skillHubFacade
        .getMyCourses(user.id)
        .then((res) => {
          const myCourses = res.data.data || [];
          const isEnrolled = myCourses.some(
            (item) => Number(item.course_id) === Number(id)
          );

          if (isEnrolled) {
            setEnrolled(true);
            setEnrollmentMessage("You are enrolled in this course.");
          }
        })
        .catch(() => {});

      skillHubFacade
        .getCourseProgress(user.id, id)
        .then((res) => setProgress(res.data.data))
        .catch(() => setProgress(null));

      skillHubFacade
        .getLastAccessedLesson(user.id, id)
        .then((res) => setLastLesson(res.data.data))
        .catch(() => setLastLesson(null));

      skillHubFacade
        .getLessonsByCourse(id)
        .then((res) => setLessons(res.data.data || res.data))
        .catch(() => setLessons([]));

      skillHubFacade
        .getMaterialsByCourse(id)
        .then((res) => setMaterials(res.data.data || []))
        .catch(() => setMaterials([]));
    }
  }, [id]);

  const notifyUser = (message) => {
    if (!user) return;

    skillHubFacade
      .createNotification({
        user_id: user.id,
        message,
      })
      .catch(() => {});
  };

  const handleEnroll = () => {
    if (!user) {
      alert("Please log in to enroll.");
      return;
    }

    skillHubFacade
      .enrollInCourse({ user_id: user.id, course_id: Number(id) })
      .then((res) => {
        setEnrolled(true);

        if (
          res.data.message &&
          res.data.message.toLowerCase().includes("already")
        ) {
          setEnrollmentMessage("You are already enrolled in this course.");
        } else {
          setEnrollmentMessage("Enrollment successful!");
          notifyUser(`You enrolled in ${course.title}.`);
        }

        return Promise.all([
          skillHubFacade.getLessonsByCourse(id),
          skillHubFacade.getMaterialsByCourse(id),
          skillHubFacade.getCourseProgress(user.id, id),
        ]);
      })
      .then(([lessonsRes, materialsRes, progressRes]) => {
        setLessons(lessonsRes.data.data || lessonsRes.data);
        setMaterials(materialsRes.data.data || []);
        setProgress(progressRes.data.data);
      })
      .catch(() => {
        setEnrolled(true);
        setEnrollmentMessage("You are already enrolled in this course.");
      });
  };

  const handleMarkCompleted = (lessonId) => {
    if (!user) {
      alert("Please log in.");
      return;
    }

    skillHubFacade
      .markLessonCompleted({
        user_id: user.id,
        course_id: Number(id),
        lesson_id: lessonId,
      })
      .then(() => skillHubFacade.getCourseProgress(user.id, id))
      .then((res) => {
        setProgress(res.data.data);
        notifyUser(`Lesson completed in ${course.title}.`);
        alert("Lesson marked as completed!");
      })
      .catch(() => alert("Failed to save progress."));
  };

  const handleGenerateCertificate = () => {
    if (!user) {
      alert("Please log in.");
      return;
    }

    skillHubFacade
      .createCertificate({
        user_id: user.id,
        course_id: Number(id),
      })
      .then((res) => {
        setCertificate(res.data.data);

        if (res.data.message && res.data.message.includes("already")) {
          setCertificateMessage("Certificate already exists.");
        } else {
          setCertificateMessage("Certificate generated successfully!");
          notifyUser(`Certificate generated for ${course.title}.`);
        }
      })
      .catch(() => alert("Failed to generate certificate."));
  };

  const handleOpenLesson = (lesson) => {
    const newOpenedLesson = openedLesson === lesson.id ? null : lesson.id;
    setOpenedLesson(newOpenedLesson);

    if (!user || newOpenedLesson === null) return;

    skillHubFacade
      .saveLastAccessedLesson({
        user_id: user.id,
        course_id: Number(id),
        lesson_id: lesson.id,
      })
      .then(() => setLastLesson({ lesson_id: lesson.id, title: lesson.title }))
      .catch(() => {});
  };

  const handleDownloadCertificate = () => {
    if (!certificate) return;

    const certificateContent = `
      <html>
        <body style="font-family: Arial, sans-serif; background:#f5f3ff; padding:40px;">
          <div style="max-width:900px; margin:0 auto; background:white; border:8px solid #7c3aed; border-radius:20px; padding:60px; text-align:center;">
            <h1 style="color:#7c3aed; font-size:42px;">Certificate of Completion</h1>
            <p style="font-size:20px;">This certificate is proudly presented to</p>
            <h2 style="font-size:36px;">${user.name}</h2>
            <p style="font-size:20px;">for successfully completing the course</p>
            <h2 style="font-size:30px; color:#7c3aed;">${course.title}</h2>
            <p>Certificate Code</p>
            <strong>${certificate.certificate_code}</strong>
            <p style="margin-top:40px;">Issued by <strong>SkillHub</strong></p>
            <p>Date: ${new Date().toLocaleDateString()}</p>
          </div>
        </body>
      </html>
    `;

    const blob = new Blob([certificateContent], { type: "text/html" });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");

    link.href = url;
    link.download = `${course.title}-certificate.html`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  };

  const handleDownloadMaterial = (material) => {
    window.location.href = `http://localhost:8000/materials/download/${material.id}`;
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
        notifyUser(`You left a review for ${course.title}.`);
        return skillHubFacade.getCourseReviews(id);
      })
      .then((res) => setReviews(res.data.data || []))
      .catch(() => alert("Failed to submit review."));
  };

  if (loading) return <p className="loading">Loading course...</p>;
  if (error) return <p className="error">{error}</p>;

  return (
    <div className="course-details">
      <div className="course-details-main">
        <div className="course-details-header">
          <h1>{course.title}</h1>
          <p>{course.description}</p>
          <p className="course-details-price">${course.price}</p>

          {user ? (
            enrolled ? (
              <p className="enrolled-msg">
                {enrollmentMessage || "You are enrolled in this course!"}
              </p>
            ) : (
              <button className="btn-enroll" onClick={handleEnroll}>
                Enroll Now
              </button>
            )
          ) : (
            <p className="enrolled-msg">
              Please log in to enroll and access course content.
            </p>
          )}

          {canAccessContent && (
            <a href={`/quiz/${id}`} className="btn-quiz">
              Take Quiz
            </a>
          )}
        </div>

        {!user && (
          <div className="course-info-card">
            <h2>Course Content Locked</h2>
            <p>
              Lessons, materials, quiz and progress tracking are available only
              after login and enrollment.
            </p>
          </div>
        )}

        {canAccessContent && (
          <div className="course-lessons">
            <h2>Lessons</h2>

            {lessons.length > 0 ? (
              lessons.map((lesson) => (
                <div
                  key={lesson.id}
                  style={{
                    border: "1px solid #ddd",
                    borderRadius: "8px",
                    padding: "12px",
                    marginBottom: "10px",
                    cursor: "pointer",
                  }}
                >
                  <h4 onClick={() => handleOpenLesson(lesson)}>
                    {lesson.title}
                  </h4>

                  {openedLesson === lesson.id && (
                    <div style={{ marginTop: "10px" }}>
                      <p>{lesson.content}</p>
                      <button
                        onClick={() => handleMarkCompleted(lesson.id)}
                        style={{
                          marginTop: "10px",
                          background: "#7c3aed",
                          color: "white",
                          border: "none",
                          padding: "8px 15px",
                          borderRadius: "5px",
                          cursor: "pointer",
                        }}
                      >
                        Mark as Completed
                      </button>
                    </div>
                  )}
                </div>
              ))
            ) : (
              <p>No lessons available yet.</p>
            )}
          </div>
        )}
      </div>

      {canAccessContent && (
        <div className="course-details-sidebar">
          {progress && (
            <div className="course-sidebar-card">
              <h3>Your Progress</h3>
              <p>
                {progress.completed_lessons} / {progress.total_lessons} lessons
                completed
              </p>

              <div
                style={{
                  width: "100%",
                  height: "20px",
                  background: "#eee",
                  borderRadius: "10px",
                }}
              >
                <div
                  style={{
                    width: `${progress.progress_percentage}%`,
                    height: "100%",
                    background: "#7c3aed",
                    borderRadius: "10px",
                  }}
                />
              </div>

              <p style={{ marginTop: "10px" }}>
                {progress.progress_percentage}% completed
              </p>

              {progress.progress_percentage === 100 && (
                <div style={{ marginTop: "15px" }}>
                  <button
                    onClick={handleGenerateCertificate}
                    style={{
                      background: "#7c3aed",
                      color: "white",
                      border: "none",
                      padding: "10px 15px",
                      borderRadius: "5px",
                      cursor: "pointer",
                    }}
                  >
                    Generate Certificate
                  </button>

                  {certificateMessage && (
                    <p style={{ marginTop: "10px" }}>{certificateMessage}</p>
                  )}

                  {certificate && (
                    <div style={{ marginTop: "10px" }}>
                      <strong>Certificate Code:</strong>{" "}
                      {certificate.certificate_code}
                      <br />
                      <br />
                      <button
                        onClick={handleDownloadCertificate}
                        style={{
                          background: "#16a34a",
                          color: "white",
                          border: "none",
                          padding: "10px 15px",
                          borderRadius: "5px",
                          cursor: "pointer",
                        }}
                      >
                        Download Certificate
                      </button>
                    </div>
                  )}
                </div>
              )}
            </div>
          )}

          {lastLesson && (
            <div className="course-sidebar-card resume-card">
              <h3>Resume Learning</h3>
              <p>
                Last lesson: <strong>{lastLesson.title}</strong>
              </p>
              <p>Continue where you left off.</p>
            </div>
          )}


          <div className="course-sidebar-card">
            <h2>Course Materials</h2>

            {materials.length === 0 ? (
              <p>No materials uploaded yet.</p>
            ) : (
              materials.map((material) => (
                <div
                  key={material.id}
                  style={{
                    display: "flex",
                    justifyContent: "space-between",
                    alignItems: "center",
                    gap: "10px",
                    padding: "10px 0",
                    borderBottom: "1px solid #eee",
                  }}
                >
                  <span>📄 {material.title}</span>

                  <div style={{ display: "flex", gap: "8px" }}>
                    <a
                      href={`http://localhost:8000/${material.file_path}`}
                      target="_blank"
                      rel="noreferrer"
                      style={{
                        background: "#a855f7",
                        color: "white",
                        padding: "8px 12px",
                        borderRadius: "5px",
                        textDecoration: "none",
                        fontWeight: "bold",
                      }}
                    >
                      View
                    </a>

                    <button
                      onClick={() => handleDownloadMaterial(material)}
                      style={{
                        background: "#7c3aed",
                        color: "white",
                        padding: "8px 12px",
                        borderRadius: "5px",
                        border: "none",
                        cursor: "pointer",
                        fontWeight: "bold",
                      }}
                    >
                      Download
                    </button>
                  </div>
                </div>
              ))
            )}
          </div>
        </div>
      )}

      <div className="course-reviews">
        <h2>Reviews</h2>

        {reviews.length > 0 ? (
          reviews.map((review) => (
            <div key={review.id} className="review-card">
              <p className="review-author">{review.user_name || review.name}</p>
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