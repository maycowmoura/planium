
export default function Loader({ text }) {
  return (
    <div id="loader" className="text-center">
      <div className="spinner-border" role="status"></div>
      <h3 className="mt-2">{text}</h3>
    </div>
  );
}
