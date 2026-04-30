export const sortByTitle = (courses) => {
  return [...courses].sort((a, b) => a.title.localeCompare(b.title));
};

export const sortByPriceLowToHigh = (courses) => {
  return [...courses].sort((a, b) => Number(a.price) - Number(b.price));
};

export const sortByPriceHighToLow = (courses) => {
  return [...courses].sort((a, b) => Number(b.price) - Number(a.price));
};

export const filterByCategory = (courses, categoryId) => {
  if (!categoryId) return courses;
  return courses.filter(
    (course) => course.category_id === Number(categoryId)
  );
};

export const courseSortStrategies = {
  title: sortByTitle,
  priceLow: sortByPriceLowToHigh,
  priceHigh: sortByPriceHighToLow,
};